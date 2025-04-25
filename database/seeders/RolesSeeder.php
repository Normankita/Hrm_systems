<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = array(
            [
                'name' => 'ADMIN',
                'guard_name' => 'web',
            ],
            [
                'name' => 'HR_OFFICER',
                'guard_name' => 'web',
            ],
            [
                'name' => 'PAYROLL_MANAGER',
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
            \Spatie\Permission\Models\Role::create($role);
        }
    }
}
