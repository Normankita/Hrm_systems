<?php

namespace App\Http\Services;

use App\Models\Contribution;
use App\Models\Employee;
use App\Models\Payroll;
use Carbon\Carbon;

class PayrollService
{
    public static function generatePayrollForAllEmployees()
    {
        $today = Carbon::today();
        $employees = Employee::with(['pay_grades' => fn($q) => $q->wherePivot('status', true)])
            ->with('deductions') // Load deductions
            ->get();

        $generated = [];

        foreach ($employees as $employee) {
            $activePaygrade = $employee->pay_grades->first();
            if (!$activePaygrade){
                continue;
            }

            $basic = $activePaygrade->pivot->base_salary_override>0 ?$activePaygrade->pivot->base_salary_override: $activePaygrade->base_salary;
            $allowances = 0;

            // Check if payroll for this employee and period already exists
            $period = $today->format('Y-m');

            $exists = Payroll::where('employee_id', $employee->id)
                ->where('period', $period)
                ->exists();
            if ($exists) {
                continue;
            }
            // Calculate statutory contributions
            $contributions = Contribution::pluck('percent', 'name'); // ['PSSF' => 6, ...]
            $paye = $basic * ($contributions['PAYE'] ?? 0) / 100;
            $nssf = $basic * ($contributions['NSSF'] ?? 0) / 100;
            $psssf = $basic * ($contributions['PSSSF'] ?? 0) / 100;
            $sdl = $basic * ($contributions['SDL'] ?? 0) / 100;
            $wcf = $basic * ($contributions['WCF'] ?? 0) / 100;

            $statutory = $paye + $nssf + $psssf + $sdl + $wcf;

            // Handle custom deductions (loan, etc.)
            $customDeductions = 0;
            $deductionsToAttach = [];

            foreach ($employee->deductions as $deduction) {
                $appliedCount = $deduction->payrolls()->count(); // how many times applied

                if ($appliedCount < $deduction->installments) {
                    $customDeductions += $deduction->installment_amount;
                    $deductionsToAttach[] = ['id' => $deduction->id, 'amount' => $deduction->installment_amount];
                }
            }

            $totalDeductions = $statutory + $customDeductions;
            $gross = $basic + $allowances;
            $net = $gross - $totalDeductions;

            $payroll = Payroll::create([
                'employee_id' => $employee->id,
                'payroll_date' => $today,
                'pay_grade_id'  => $activePaygrade->id,
                'basic_salary' => $basic,
                'allowances' => $allowances,
                'deductions' => $customDeductions,
                'gross_salary' => $gross,
                'net_salary' => $net,
                'period' => $period,
                'paye' => $paye,
                'nssf' => $nssf,
                'psssf' => $psssf,
                'sdl' => $sdl,
                'wcf' => $wcf,
            ]);

            // Attach deductions
            foreach ($deductionsToAttach as $item) {
                $payroll->deductions()->attach($item['id'], ['amount' => $item['amount']]);
            }

            $generated[] = $payroll;
        }

        return $generated;
    }
}
