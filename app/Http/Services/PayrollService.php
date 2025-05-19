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
        $period = $today->format('Y-m');

        $employees = Employee::with([
            'pay_grades' => fn($q) => $q->wherePivot('status', true),
            'deductions'
        ])->get();

        $generated = [];

        foreach ($employees as $employee) {
            $activePayGrade = $employee->pay_grades->first();
            if (!$activePayGrade)
                continue;

            $basic = $activePayGrade->pivot->base_salary_override > 0
                ? $activePayGrade->pivot->base_salary_override
                : $activePayGrade->base_salary;

            $allowances = 0;

            // Skip if payroll for this employee and period already exists
            if (Payroll::where('employee_id', $employee->id)->where('period', $period)->exists()) {
                continue;
            }

            // Get statutory contribution percentages
            $contributions = Contribution::pluck('percent', 'name');

            $paye = $basic * ($contributions['PAYE'] ?? 0) / 100;
            $nssf = $basic * ($contributions['NSSF'] ?? 0) / 100;
            $psssf = $basic * ($contributions['PSSSF'] ?? 0) / 100;
            $sdl = $basic * ($contributions['SDL'] ?? 0) / 100;
            $wcf = $basic * ($contributions['WCF'] ?? 0) / 100;

            $statutory = $paye + $nssf + $psssf + $sdl + $wcf;

            // Handle custom deductions (loans, penalties, etc.)
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
                        'amount' => $deduction->installment_amount,
                    ];
                }
            }

            $totalDeductions = $statutory + $customDeductions;
            $gross = $basic + $allowances;
            $net = $gross - $totalDeductions;

            // Create payroll record
            $payroll = Payroll::create([
                'employee_id' => $employee->id,
                'pay_grade_id' => $activePayGrade->id,
                'payroll_date' => $today,
                'period' => $period,
                'basic_salary' => $basic,
                'allowances' => $allowances,
                'deductions' => $customDeductions,
                'gross_salary' => $gross,
                'net_salary' => $net,
                'paye' => $paye,
                'nssf' => $nssf,
                'psssf' => $psssf,
                'sdl' => $sdl,
                'wcf' => $wcf,
            ]);

            // Attach applicable deductions with amount to pivot table
            foreach ($deductionsToAttach as $item) {
                $payroll->deductions()->attach($item['id'], [
                    'amount' => $item['amount']
                ]);
            }

            $generated[] = $payroll;
        }

        return $generated;
    }
}
