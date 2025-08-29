<?php

namespace Database\Seeders;

use App\Models\AttendanceSession;
use App\Models\Company;
use App\Models\Contribution;
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
        $company = Company::first();

        // 1.5 create company payment date
        $settings = [
            ['name' => 'payment_date', 'value' => 27]
        ];
        foreach ($settings as $setting) {
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
        PayGrade::create([
            'name' => 'Default Grade',
            'base_salary' => 50000,
            'max_salary' => 70000,
            'base_month_count' => 12,
            'company_id' => $company->id,
            'description' => 'Default pay grade for initial employees',
        ]);

        $defaultSession = AttendanceSession::create([
            'company_id' => $company->id,
            'session_type' => 'full_day',
            'start_time' => '02:00',
            'end_time' => '17:00',
            'is_active' => true,
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
            'name' => 'john mafongo Sample',
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
            'attendance_session_id' => $defaultSession->id,
            'full_name' => $emp->name,
            'gender' => 'male',
            'date_of_birth' => '1990-01-01',
            'phone_number' => '1234567890',
            'email' => $emp->email,
            'national_id' => '123456789',
            'marital_status' => 'Single',
            'residential_address' => '123 Main St',
            'tin_number' => '123456789',
            'employee_type' => 'permanent',
            'date_of_hire' => now(),
            'salary' => 50000,
            'profile_picture' => '',
        ]);

        // 8. Seed statutory contributions
        $contributions = [
            ['name' => 'PAYE', 'percent' => 0, 'description' => 'Income Tax'],
            ['name' => 'NSSF', 'percent' => 0, 'description' => 'Social Security Fund'],
            ['name' => 'PSSSF', 'percent' => 0, 'description' => 'Pension Scheme'],
            ['name' => 'SDL', 'percent' => 0, 'description' => 'Skills Development Levy'],
            ['name' => 'WCF', 'percent' => 0, 'description' => 'Workers Compensation Fund'],
        ];
        foreach ($contributions as $c) {
            $c = array_merge(['company_id' => $company->id], $c);
            Contribution::create($c);
        }
    }
}
