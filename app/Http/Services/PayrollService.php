<?php

namespace App\Http\Services;

use App\Http\Utils\Traits\PdfTrait;
use App\Models\Contribution;
use App\Models\Employee;
use App\Models\EmployeeAllowance;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    use PdfTrait;

    public static function generatePayrollForAllEmployees(bool $force = false): array
    {
        $employees = Employee::with([
            'pay_grades' => fn($q) => $q->wherePivot('status', true),
            'deductions',
            'allowances' // eager load with pivot
        ])->get();

        return self::processPayroll($force, $employees);
    }

    public static function generatePayrollForSelectedEmployees(bool $force = false, $employeeIds = []): array
    {
        $employees = Employee::with([
            'pay_grades' => fn($q) => $q->wherePivot('status', true),
            'deductions',
            'allowances'
        ])->whereIn('id', $employeeIds)->get();

        return self::processPayroll($force, $employees);
    }

    public static function processPayroll(bool $force = false, $employees = []): array
    {
        $today = Carbon::today();
        $period = $today->format('Y-m');
        $todayDate = $today->format('Y-m-d');

        $contributions = Contribution::pluck('percent', 'name');
        $generated = [];

        foreach ($employees as $employee) {
            $activePayGrade = $employee->pay_grades->first();

            if (!$activePayGrade)
                continue;

            $basic = $employee->getBaseSalary();

            // Fetch valid employee allowances
            $employeeAllowances = $employee->allowances()
                ->wherePivot('status', true)
                ->wherePivot('effective_from', '<=', $todayDate)
                ->wherePivot('effective_to', '>=', $todayDate)
                ->get();
            $employeeAllowancesTotal = 0;
            $allowancesToAttach = [];

            foreach ($employeeAllowances as $allowance) {
                $employeeAllowancesTotal += $allowance->pivot->amount;

                // Fetch the actual pivot row ID (EmployeeAllowance)
                $employeeAllowance = EmployeeAllowance::where('employee_id', $employee->id)
                    ->where('allowance_id', $allowance->id)
                    ->where('status', true)
                    ->where('effective_from', '<=', $todayDate)
                    ->where('effective_to', '>=', $todayDate)
                    ->first();

                if ($employeeAllowance) {
                    $allowancesToAttach[] = [
                        'employee_allowance_id' => $employeeAllowance->id,
                        'amount' => $allowance->pivot->amount,
                    ];
                }
            }

            // Handle regeneration
            if ($force) {
                Payroll::where('employee_id', $employee->id)->where('period', $period)->delete();
            } else {
                if (Payroll::where('employee_id', $employee->id)->where('period', $period)->exists()) {
                    continue;
                }
            }

            // Statutory deductions
            $paye = $basic * ($contributions['PAYE'] ?? 0) / 100;
            $nssf = $basic * ($contributions['NSSF'] ?? 0) / 100;
            $psssf = $basic * ($contributions['PSSSF'] ?? 0) / 100;
            $sdl = $basic * ($contributions['SDL'] ?? 0) / 100;
            $wcf = $basic * ($contributions['WCF'] ?? 0) / 100;

            $statutory = $paye + $nssf + $psssf + $sdl + $wcf;

            // Custom deductions (e.g., loans)
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
            $gross = $basic + $employeeAllowancesTotal;
            $net = $gross - $totalDeductions;

            DB::beginTransaction();
            try {
                $payroll = Payroll::create([
                    'employee_id' => $employee->id,
                    'pay_grade_id' => $activePayGrade->id,
                    'payroll_date' => $today,
                    'period' => $period,
                    'basic_salary' => $basic,
                    'allowances' => $employeeAllowancesTotal,
                    'deductions' => $customDeductions,
                    'gross_salary' => $gross,
                    'net_salary' => $net,
                    'paye' => $paye,
                    'nssf' => $nssf,
                    'psssf' => $psssf,
                    'sdl' => $sdl,
                    'wcf' => $wcf,
                ]);
                foreach ($deductionsToAttach as $item) {
                    $payroll->deductions()->attach($item['id'], [
                        'total_amount' => $item['total_amount']
                    ]);
                }

                // bad zone where allowances in payroll are set to zero

                foreach ($allowancesToAttach as $item) {
                    $payroll->employeeAllowances()->attach($item['employee_allowance_id'], [
                        'amount' => $item['amount']
                    ]);
                }

                // ends here

                $pdfService = new PayslipPdfService();

                $path = $pdfService->generate($payroll);
                $payroll->update(['payslip_path' => $path]);

                DB::commit();
                $generated[] = $payroll;
            } catch (\Throwable $e) {

                dd($e);
                DB::rollBack();
                // optionally log the error: \Log::error($e);
                continue;
            }
        }

        return $generated;
    }
}
