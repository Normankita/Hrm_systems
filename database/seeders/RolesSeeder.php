<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first();
        $roles = array(
            [
                'name' => 'ADMIN',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EMPLOYEE',
                'guard_name' => 'web',
            ],
            [
                'name' => 'OWNER',
                'guard_name' => 'web',
            ],
        );

        // create the actual roles
        foreach ($roles as $role) {
            $role = array_merge($role, ['company_id' => $company->id]); 
            Role::create($role);
        }
    }
}
