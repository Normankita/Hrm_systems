<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create a default company for our admin user
        $company = array(
            'name' => 'Default Company',
        );

        $company = Company::create($company);
        Department::create([
            'company_id' => $company->id,
            'name' => 'Default Department',
            'code' => 'HR',
            'description' => 'Default department for the company',
        ]);



        $admin = array(
            'name' => 'Admin',
            'company_id' => $company->id,
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => null,
        );
        // create the actual user
        $user = User::create($admin);
        // assign role
        $adminRole = Role::findByName('ADMIN');
        $user->assignRole($adminRole);


        $employeeUser = array(
            'name' => 'john mafongo',
            'company_id' => $company->id,
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => null,
        );
        // create the actual user
        $emp = User::create($employeeUser);
        // assign role
        $employeeRole = Role::findByName('EMPLOYEE');
        $emp->assignRole($employeeRole);
        Employee::create([
            'user_id' => $emp->id,
            'company_id' => $company->id,
            'department_id' => 1,
            'full_name' => 'employee User',
            'gender' => 'male',
            'date_of_birth' => '1990-01-01',
            'phone_number' => '1234567890',
            'email' => 'john@gmail.co',
            'national_id' => '123456789',
            'marital_status' => 'single',
            'residential_address' => '123 Main St',
            'tin_number' => '123456789',
            'employee_type' => 'Permanent',
            'date_of_hire' => now(),
            'date_of_termination' => null,
            'salary' => 50000,
        ]);



        $hr = array(
            'name' => 'hr mafongo',
            'company_id' => $company->id,
            'email' => 'hr@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => null,
        );
        // create the actual user
        $hr = User::create($hr);
        // assign role
        $hrRole = Role::findByName('HR_OFFICER');
        $hr->assignRole($hrRole);
        Employee::create([
            'user_id' => $hr->id,
            'company_id' => $company->id,
            'department_id' => 1,
            'full_name' => 'hr User',
            'gender' => 'male',
            'date_of_birth' => '1990-01-01',
            'phone_number' => '12349567890',
            'email' => 'hr@gmail.co',
            'national_id' => '12345678989',
            'marital_status' => 'single',
            'residential_address' => '123 Main St',
            'tin_number' => '12345698789',
            'employee_type' => 'Permanent',
            'date_of_hire' => now(),
            'date_of_termination' => null,
            'salary' => 50000,
        ]);
    }
}
