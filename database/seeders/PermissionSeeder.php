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
                'group_name' => 'employees',
                'slug' => 'view-all-employees',
                'division' => 'group'
            ],
            [
                'name' => 'create_employees',
                'guard_name' => 'web',
                'group_name' => 'employees',
                'slug' => 'create-any-employee',
                'division' => 'group'
            ],
            [
                'name' => 'edit_employees',
                'guard_name' => 'web',
                'group_name' => 'employees',
                'slug' => 'edit-any-employee',
                'division' => 'group'
            ],
            [
                'name' => 'delete_employees',
                'guard_name' => 'web',
                'group_name' => 'employees',
                'slug' => 'delete-any-employee',
                'division' => 'group'
            ],



            [
                'name' => 'view_payroll',
                'guard_name' => 'web',
                'group_name' => 'payroll',
                'slug' => 'view-all-payroll',
                'division' => 'group'
            ],
            [
                'name' => 'create_payroll',
                'guard_name' => 'web',
                'group_name' => 'payroll',
                'slug' => 'create-any-payroll',
                'division' => 'group'
            ],
            [
                'name' => 'edit_payroll',
                'guard_name' => 'web',
                'group_name' => 'payroll',
                'slug' => 'edit-any-payroll',
                'division' => 'group'
            ],
            [
                'name' => 'delete_payroll',
                'guard_name' => 'web',
                'group_name' => 'payroll',
                'slug' => 'delete-any-payroll',
                'division' => 'group'
            ],




            [
                'name' => 'view_paygrade',
                'guard_name' => 'web',
                'group_name' => 'paygrade',
                'slug' => 'view-all-paygrade',
                'division' => 'group'
            ],
            [
                'name' => 'create_paygrade',
                'guard_name' => 'web',
                'group_name' => 'paygrade',
                'slug' => 'create-any-paygrade',
                'division' => 'group'
            ],
            [
                'name' => 'edit_paygrade',
                'guard_name' => 'web',
                'group_name' => 'paygrade',
                'slug' => 'edit-any-paygrade',
                'division' => 'group'
            ],
            [
                'name' => 'delete_paygrade',
                'guard_name' => 'web',
                'group_name' => 'paygrade',
                'slug' => 'delete-any-paygrade',
                'division' => 'group'
            ],




            [
                'name' => 'view_leave',
                'guard_name' => 'web',
                'group_name' => 'leave',
                'slug' => 'view-leave',
                'division' => 'individual'
            ],
            [
                'name' => 'request_leave',
                'guard_name' => 'web',
                'group_name' => 'leave',
                'slug' => 'request-leave',
                'division' => 'individual'
            ],
            [
                'name' => 'edit_leave',
                'guard_name' => 'web',
                'group_name' => 'leave',
                'slug' => 'edit-leave',
                'division' => 'individual'
            ],
            [
                'name' => 'delete_leave',
                'guard_name' => 'web',
                'group_name' => 'leave',
                'slug' => 'delete-leave',
                'division' => 'individual'
            ],


            [
                'name' => 'view_leave_requests',
                'guard_name' => 'web',
                'group_name' => 'leave_response',
                'slug' => 'view-all-leave-requests',
                'division' => 'group'
            ],
            [
                'name' => 'respond_leave_request',
                'guard_name' => 'web',
                'group_name' => 'leave_response',
                'slug' => 'respond-any-leave-request',
                'division' => 'group'
            ],
            // [
            //     'name' => 'edit_leave_response',
            //     'guard_name' => 'web',
            //     'group_name' => 'leave_response',
            //     'slug' => 'edit-any-leave-response',
            //     'division' => 'group'
            // ],
            // [
            //     'name' => 'delete_leave_response',
            //     'guard_name' => 'web',
            //     'group_name' => 'leave_response',
            //     'slug' => 'delete-any-leave-response',
            //     'division' => 'group'
            // ],


            [
                'name' => 'view_leaveTypes',
                'guard_name' => 'web',
                'group_name' => 'leaveType',
                'slug' => 'view-all-leaveTypes',
                'division' => 'group'
            ],
            [
                'name' => 'create_leaveType',
                'guard_name' => 'web',
                'group_name' => 'leaveType',
                'slug' => 'create-any-leaveType',
                'division' => 'group'
            ],
            [
                'name' => 'edit_leaveType',
                'guard_name' => 'web',
                'group_name' => 'leaveType',
                'slug' => 'edit-any-leaveType',
                'division' => 'group'
            ],
            [
                'name' => 'delete_leaveType',
                'guard_name' => 'web',
                'group_name' => 'leaveType',
                'slug' => 'delete-any-leaveType',
                'division' => 'group'
            ],

            [
                'name'=> 'edit_own_employees',
                'guard_name' => 'web',
                'group_name' => 'employee',
                'slug' => 'edit-own-employees',
                'division' => 'individual'
            ],
            [
                'name'=> 'view_own_payrolls',
                'guard_name'=> 'web',
                'group_name'=> 'employee',
                'slug'=> 'view-own-payrolls',
                'division'=> 'individual'
            ],
            [
                'name'=>'view_attachments',
                'guard_name'=>'web',
                'group_name'=>'employee',
                'slug'=> 'view-attachments',
                'division'=>'individual'
            ],


            [
                'name'=>'view_deductions',
                'guard_name'=>'web',
                'group_name'=>'deductions',
                'slug'=> 'view-deductions',
                'division'=>'group'
            ],
            [
                'name'=> 'create_deductions',
                'guard_name' => 'web',
                'group_name' => 'deductions',
                'slug' => 'create-deductions',
                'division' => 'group'
            ],
            [
                'name'=> 'edit_deductions',
                'guard_name' => 'web',
                'group_name' => 'deductions',
                'slug' => 'edit-deductions',
                'division' => 'group'
            ],
            [
                'name'=> 'delete_deductions',
                'guard_name' => 'web',
                'group_name' => 'deductions',
                'slug' => 'delete-deductions',
                'division' => 'group'
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
