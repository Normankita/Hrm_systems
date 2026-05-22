<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EmployeeRelationsPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'view_employee_relations',
                'guard_name' => 'web',
                'group_name' => 'employee_relations',
                'slug' => 'view-employee-relations',
                'division' => 'group',
            ],
            [
                'name' => 'edit_employee_relations',
                'guard_name' => 'web',
                'group_name' => 'employee_relations',
                'slug' => 'edit-employee-relations',
                'division' => 'group',
            ],
            [
                'name' => 'download_employee_relations',
                'guard_name' => 'web',
                'group_name' => 'employee_relations',
                'slug' => 'download-employee-relations',
                'division' => 'group',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                $permission
            );
        }

        $names = array_map(fn ($p) => $p['name'], $permissions);

        $adminRole = Role::findByName('ADMIN');
        $adminRole->givePermissionTo($names);

        $employeeRole = Role::where('name', 'EMPLOYEE')->first();
        if ($employeeRole) {
            $employeeRole->givePermissionTo($names);
        }
    }
}
