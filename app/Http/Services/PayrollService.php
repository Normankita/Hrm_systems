<?php

namespace App\Http\Services;

use App\Models\Employee;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PayrollService
{
    public static function generatePayrollForAllEmployees()
    {
        $today = Carbon::today();
        $period = $today->format('Y-m');

        $employees = Employee::with(['pay_grades' => function ($query) {
            $query->wherePivot('status', true);
        }])->get();

        $generated = [];

        foreach ($employees as $employee) {
            $activePayGrade = $employee->pay_grades->first();

            if (!$activePayGrade) {
                continue;
            }
            // dd($activePayGrade->base_salary);

            // Check if payroll for this employee and period already exists
            $exists = Payroll::where('employee_id', $employee->id)
                ->where('period', $period)
                ->exists();

            if ($exists) {
                continue;
            }
            $basic = $activePayGrade->pivot->base_salary_override>0? $activePayGrade->pivot->base_salary_override: $activePayGrade->base_salary ;
            $allowances = 0;
            $deductions = 0;

            // Placeholder values (custom logic may be added later)
            $paye = 0;
            $nssf = 0;
            $psssf = 0;
            $sdl = 0;
            $wcf = 0;

            $gross = $basic + $allowances;
            $net = $gross - ($deductions + $paye + $nssf + $psssf + $sdl + $wcf);

            $payroll = Payroll::create([
                'employee_id'   => $employee->id,
                'pay_grade_id'  => $activePayGrade->id,
                'payroll_date'  => $today,
                'period'        => $period,
                'basic_salary'  => $basic,
                'gross_salary'  => $gross,
                'net_salary'    => $net,
                'paye'          => $paye,
                'nssf'          => $nssf,
                'psssf'         => $psssf,
                'sdl'           => $sdl,
                'wcf'           => $wcf,
                'allowances'    => $allowances,
                'deductions'    => $deductions,
                'status'        => 'Pending',
                'payslip_path'  => null,
            ]);

            $generated[] = $payroll;
        }

        return $generated;
    }
}
