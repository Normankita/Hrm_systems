<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\PayGrade;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddingPaygradeToDefaultEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = Employee::where('email', 'john@example.com')
            ->first();
        $payGrade = PayGrade::where('name', 'Default Grade')->first();
        $admin = User::where('email', 'admin@example.com')->first();

        $employee->pay_grades()->attach($payGrade->id, [
            'assigned_by' => $admin->id,
            'effective_from' => now(),
            'base_salary_override' => $employee->salary,
            'status' => true
        ]);

    }
}
