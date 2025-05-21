<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = array(
            [
                'name' => 'view_employees',
                'guard_name' => 'web',
                'group_name' => 'employees'
            ],
            [
                'name' => 'create_employees',
                'guard_name' => 'web',
                'group_name' => 'employees'
            ],
            [
                'name' => 'edit_employees',
                'guard_name' => 'web',
                'group_name' => 'employees'
            ],
            [
                'name' => 'delete_employees',
                'guard_name' => 'web',
                'group_name' => 'employees'
            ],



            [
                'name' => 'view_payroll',
                'guard_name' => 'web',
                'group_name' => 'payroll'
            ],
            [
                'name' => 'create_payroll',
                'guard_name' => 'web',
                'group_name' => 'payroll'
            ],
            [
                'name' => 'edit_payroll',
                'guard_name' => 'web',
                'group_name' => 'payroll'
            ],
            [
                'name' => 'delete_payroll',
                'guard_name' => 'web',
                'group_name' => 'payroll'
            ],




            [
                'name' => 'view_paygrade',
                'guard_name' => 'web',
                'group_name' => 'paygrade'
            ],
            [
                'name' => 'create_paygrade',
                'guard_name' => 'web',
                'group_name' => 'paygrade'
            ],
            [
                'name' => 'edit_paygrade',
                'guard_name' => 'web',
                'group_name' => 'paygrade'
            ],
            [
                'name' => 'delete_paygrade',
                'guard_name' => 'web',
                'group_name' => 'paygrade'
            ],




            [
                'name' => 'view_leave',
                'guard_name' => 'web',
                'group_name' => 'leave'
            ],
            [
                'name' => 'create_leave',
                'guard_name' => 'web',
                'group_name' => 'leave'
            ],
            [
                'name' => 'edit_leave',
                'guard_name' => 'web',
                'group_name' => 'leave'
            ],
            [
                'name' => 'delete_leave',
                'guard_name' => 'web',
                'group_name' => 'leave'
            ],


            [
                'name' => 'view_leave_response',
                'guard_name' => 'web',
                'group_name' => 'leave_response'
            ],
            [
                'name' => 'create_leave_response',
                'guard_name' => 'web',
                'group_name' => 'leave_response'
            ],
            [
                'name' => 'edit_leave_response',
                'guard_name' => 'web',
                'group_name' => 'leave_response'
            ],
            [
                'name' => 'delete_leave_response',
                'guard_name' => 'web',
                'group_name' => 'leave_response'
            ],


            [
                'name' => 'view_leaveType',
                'guard_name' => 'web',
                'group_name' => 'leaveType'
            ],
            [
                'name' => 'create_leaveType',
                'guard_name' => 'web',
                'group_name' => 'leaveType'
            ],
            [
                'name' => 'edit_leaveType',
                'guard_name' => 'web',
                'group_name' => 'leaveType'
            ],
            [
                'name' => 'delete_leaveType',
                'guard_name' => 'web',
                'group_name' => 'leaveType'
            ],

        );

        // Assign permissions to roles
        $adminRole = Role::findByName('ADMIN');

        // create the actual permissions
        foreach ($permissions as $permission) {
            Permission::create($permission);
            $adminRole->givePermissionTo($permission['name']);
        }

    }
}
