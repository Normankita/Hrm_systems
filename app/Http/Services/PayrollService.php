<?php

namespace App\Http\Services;

use App\Models\Contribution;
use App\Models\Employee;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Log;

class PayrollService
{
    /**
     * Generate payrolls for all employees.
     *
     * @param bool $force If true, existing payrolls for the current period will be deleted and regenerated.
     * @return array List of generated payrolls
     */
    public static function generatePayrollForAllEmployees(bool $force = false): array
    {
        $today = Carbon::today();
        $period = $today->format('Y-m');

        // Fetch employees with active pay grades and deductions
        $employees = Employee::with([
            'pay_grades' => fn($q) => $q->wherePivot('status', true),
            'deductions'
        ])->get();

        // Load statutory contribution rates
        $contributions = Contribution::pluck('percent', 'name');
        $generated = [];

        foreach ($employees as $employee) {
            $activePayGrade = $employee->pay_grades->first();

            if (!$activePayGrade) {
                continue;
            }

            // Determine base salary
            $basic = $activePayGrade->pivot->base_salary_override > 0
                ? $activePayGrade->pivot->base_salary_override
                : $activePayGrade->base_salary;

            $allowances = 0;

            // Handle regeneration: delete existing payroll if $force = true
            if ($force) {
                Payroll::where('employee_id', $employee->id)->where('period', $period)->delete();
            } else {
                if (Payroll::where('employee_id', $employee->id)->where('period', $period)->exists()) {
                    continue;
                }
            }

            // Calculate statutory deductions
            $paye = $basic * ($contributions['PAYE'] ?? 0) / 100;
            $nssf = $basic * ($contributions['NSSF'] ?? 0) / 100;
            $psssf = $basic * ($contributions['PSSSF'] ?? 0) / 100;
            $sdl = $basic * ($contributions['SDL'] ?? 0) / 100;
            $wcf = $basic * ($contributions['WCF'] ?? 0) / 100;

            $statutory = $paye + $nssf + $psssf + $sdl + $wcf;

            // Handle custom deductions (e.g. loans)
            $customDeductions = 0;
            $deductionsToAttach = [];

            foreach ($employee->deductions as $deduction) {
                $appliedCount = $deduction->payrolls()
                    ->wherePivot('deduction_id', $deduction->id)
                    ->count();

                if ($appliedCount < $deduction->installments) {
                    $customDeductions += $deduction->installment_amount;
                    $deductionsToAttach[] = [
                        'id' => $deduction->id,
                        'total_amount' => $deduction->installment_amount,
                    ];
                }
            }

            $totalDeductions = $statutory + $customDeductions;
            $gross = $basic + $allowances;
            $net = $gross - $totalDeductions;

            DB::beginTransaction();

            try {
                // Create payroll record
                $payroll = Payroll::create([
                    'employee_id'    => $employee->id,
                    'pay_grade_id'   => $activePayGrade->id,
                    'payroll_date'   => $today,
                    'period'         => $period,
                    'basic_salary'   => $basic,
                    'allowances'     => $allowances,
                    'deductions'     => $customDeductions,
                    'gross_salary'   => $gross,
                    'net_salary'     => $net,
                    'paye'           => $paye,
                    'nssf'           => $nssf,
                    'psssf'          => $psssf,
                    'sdl'            => $sdl,
                    'wcf'            => $wcf,
                ]);

                // Attach deductions
                foreach ($deductionsToAttach as $item) {
                    $payroll->deductions()->attach($item['id'], [
                        'total_amount' => $item['total_amount']
                    ]);
                }

                DB::commit();
                $generated[] = $payroll;
            } catch (\Throwable $e) {
                DB::rollBack();
                
                Log::error('Payroll generation failed', ['employee_id' => $employee->id, 'error' => $e->getMessage()]);
            }
        }

        return $generated;
    }
}
