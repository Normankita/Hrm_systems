<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class EmployeeStatusPermissions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusPermissions = array(
            [
                'name' => 'view_employee_statuses',
                'guard_name' => 'web',
                'group_name' => 'status',
                'slug' => 'view-all-status',
                'division' => 'group'
            ],
            [
                'name' => 'create_employee_statuses',
                'guard_name' => 'web',
                'group_name' => 'status',
                'slug' => 'create-any-status',
                'division' => 'group'
            ],
            [
                'name' => 'edit_employee_status',
                'guard_name' => 'web',
                'group_name' => 'status',
                'slug' => 'edit-any-status',
                'division' => 'group'
            ],
            [
                'name' => 'view_employee_status_history',
                'guard_name' => 'web',
                'group_name' => 'status',
                'slug' => 'view-employee-status-history',
                'division' => 'group'
            ],
            [
                'name' => 'view_employee_status_history_by_employee',
                'guard_name' => 'web',
                'group_name' => 'status',
                'slug' => 'view-employee-status-history-by-employee',
                'division' => 'group'
            ],
            [
                'name' => 'view_employee_status_history_by_date',
                'guard_name' => 'web',
                'group_name' => 'status',
                'slug' => 'view-employee-status-history-by-date',
                'division' => 'group'
            ],
        );
        $adminRole = Role::findByName('ADMIN');

        foreach ($statusPermissions as $permission) {
            Permission::create($permission);
            $adminRole->givePermissionTo($permission['name']);
        }
    }
}
