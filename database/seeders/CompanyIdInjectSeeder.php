<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyIdInjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // in payrolls select all records
        $payrolls = \App\Models\Payroll::all();
        // on each pich their employee_id and find the company_id from employees table
        foreach ($payrolls as $payroll) {
            $employee = \App\Models\Employee::find($payroll->employee_id);
            if ($employee) {
                $payroll->company_id = $employee->company_id;
                $payroll->save();
            }
        }
    }
}
