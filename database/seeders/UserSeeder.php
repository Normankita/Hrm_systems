<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Contribution;
use App\Models\Deduction;
use App\Models\Department;
use App\Models\Employee;
use App\Models\PayGrade;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create company
        $company = Company::create(['name' => 'Default Company']);

        // 1.5 create company payment date
        $settings = [
            ['name' => 'payment_date', 'value' => 27]
        ];
        foreach($settings as $setting) {
            $setting = array_merge(['company_id' => $company->id], $setting);
            Setting::create($setting);
        }

        // 2. Create department
        $department = Department::create([
            'company_id' => $company->id,
            'name' => 'Default Department',
            'code' => 'HR',
            'description' => 'Default department for the company',
        ]);

        // 3. Create default pay grade
        $payGrade = PayGrade::create([
            'name' => 'Default Grade',
            'base_salary' => 50000,
            'max_salary' => 70000,
            'base_month_count' => 12,
            'description' => 'Default pay grade for initial employees',
        ]);

        // 4. Create Admin user
        $admin = User::create([
            'name' => 'Admin',
            'company_id' => $company->id,
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole(Role::findByName('ADMIN'));

        // 5. Create Employee user
        $emp = User::create([
            'name' => 'john mafongo',
            'company_id' => $company->id,
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_default_configs' => 1,
        ]);
        $emp->assignRole(Role::findByName('EMPLOYEE'));

        $employee = Employee::create([
            'user_id' => $emp->id,
            'company_id' => $company->id,
            'department_id' => $department->id,
            'full_name' => 'employee User',
            'gender' => 'male',
            'date_of_birth' => '1990-01-01',
            'phone_number' => '1234567890',
            'email' => 'john@gmail.co',
            'national_id' => '123456789',
            'marital_status' => 'Single',
            'residential_address' => '123 Main St',
            'tin_number' => '123456789',
            'employee_type' => 'Permanent',
            'date_of_hire' => now(),
            'salary' => 50000,
            'profile_picture' => '',
        ]);

        // 6. Create HR user
        $hr = User::create([
            'name' => 'hr mafongo',
            'company_id' => $company->id,
            'email' => 'hr@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $hr->assignRole(Role::findByName('HR_OFFICER'));
        Employee::create([
            'user_id' => $hr->id,
            'company_id' => $company->id,
            'department_id' => $department->id,
            'full_name' => 'hr User',
            'gender' => 'male',
            'date_of_birth' => '1990-01-01',
            'phone_number' => '12349567890',
            'email' => 'hr@gmail.co',
            'national_id' => '12345678989',
            'marital_status' => 'Single',
            'residential_address' => '123 Main St',
            'tin_number' => '12345698789',
            'employee_type' => 'Permanent',
            'date_of_hire' => now(),
            'salary' => 50000,
            'profile_picture' => '',
        ]);

        // 7. Create Payroll Manager
        $payroll = User::create([
            'name' => 'payroll mafongo',
            'company_id' => $company->id,
            'email' => 'payroll@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $payroll->assignRole(Role::findByName('PAYROLL_MANAGER'));
        Employee::create([
            'user_id' => $payroll->id,
            'company_id' => $company->id,
            'department_id' => $department->id,
            'full_name' => 'payroll User',
            'gender' => 'male',
            'date_of_birth' => '1990-01-01',
            'phone_number' => '1234956780',
            'email' => 'payroll@gmail.co',
            'national_id' => '1234578989',
            'marital_status' => 'Single',
            'residential_address' => '123 Main St',
            'tin_number' => '1234569789',
            'employee_type' => 'Permanent',
            'date_of_hire' => now(),
            'salary' => 50000,
            'profile_picture' => '',
        ]);

        // 8. Seed statutory contributions
        $contributions = [
            ['name' => 'PAYE', 'percent' => 9, 'description' => 'Income Tax'],
            ['name' => 'NSSF', 'percent' => 10, 'description' => 'Social Security Fund'],
            ['name' => 'PSSSF', 'percent' => 6, 'description' => 'Pension Scheme'],
            ['name' => 'SDL', 'percent' => 4, 'description' => 'Skills Development Levy'],
            ['name' => 'WCF', 'percent' => 1, 'description' => 'Workers Compensation Fund'],
        ];

        foreach ($contributions as $c) {
            Contribution::firstOrCreate(['name' => $c['name']], $c);
        }

        // 9. Seed sample employee-specific deductions
        Deduction::create([
            'name' => 'Laptop Loan',
            'description' => 'Installment for company laptop',
            'total_amount' => 600000,
            'installments' => 6,
            'installment_amount' => 100000,
            'employee_id' => $employee->id,
        ]);

        Deduction::create([
            'name' => 'Advance Salary',
            'description' => 'Advance issued in April',
            'total_amount' => 200000,
            'installments' => 2,
            'installment_amount' => 100000,
            'employee_id' => $employee->id,
        ]);
    }
}
