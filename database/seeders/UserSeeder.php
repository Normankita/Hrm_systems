<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $admin = array(
            'name' => 'Admin',
            'company_id' => $company->id,
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => null,
        );

        Department::create([
            'company_id' => $company->id,
            'name' => 'Default Department',
            'code' => 'HR',
            'description' => 'Default department for the company',
        ]);



        // create the actual user
        $user = \App\Models\User::create($admin);
        // assign role
        $adminRole = Role::findByName('ADMIN');
        $user->assignRole($adminRole);

        Employee::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'department_id' => 1,
            'full_name' => 'Admin User',
            'gender' => 'male',
            'date_of_birth' => '1990-01-01',
            'phone_number' => '1234567890',
            'email' => 'me@gmail.co',
            'national_id' => '123456789',
            'marital_status' => 'single',
            'residential_address' => '123 Main St',
            'tin_number' => '123456789',
            'employee_type' => 'Permanent',
            'date_of_hire' => now(),
            'date_of_termination' => null,
            'salary' => 50000,
        ]);
    }
}
