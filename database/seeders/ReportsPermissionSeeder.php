<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ReportsPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'view_reports',
                'guard_name' => 'web',
                'group_name' => 'reports',
                'slug' => 'view-reports-menu',
                'division' => 'group',
            ],
            [
                'name' => 'view_employee_reports',
                'guard_name' => 'web',
                'group_name' => 'reports',
                'slug' => 'view-employee-reports',
                'division' => 'group',
            ],
            [
                'name' => 'view_attendance_reports',
                'guard_name' => 'web',
                'group_name' => 'reports',
                'slug' => 'view-attendance-reports',
                'division' => 'group',
            ],
            [
                'name' => 'view_leave_reports',
                'guard_name' => 'web',
                'group_name' => 'reports',
                'slug' => 'view-leave-reports',
                'division' => 'group',
            ],
            [
                'name' => 'view_payroll_reports',
                'guard_name' => 'web',
                'group_name' => 'reports',
                'slug' => 'view-payroll-reports',
                'division' => 'group',
            ],
            [
                'name' => 'view_allowance_reports',
                'guard_name' => 'web',
                'group_name' => 'reports',
                'slug' => 'view-allowance-reports',
                'division' => 'group',
            ],
            [
                'name' => 'view_loan_reports',
                'guard_name' => 'web',
                'group_name' => 'reports',
                'slug' => 'view-loan-reports',
                'division' => 'group',
            ],
            [
                'name' => 'view_deduction_reports',
                'guard_name' => 'web',
                'group_name' => 'reports',
                'slug' => 'view-deduction-reports',
                'division' => 'group',
            ],
            [
                'name' => 'view_disbursement_reports',
                'guard_name' => 'web',
                'group_name' => 'reports',
                'slug' => 'view-disbursement-reports',
                'division' => 'group',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                $permission
            );
        }

        $adminRole = Role::findByName('ADMIN');
        $adminRole->givePermissionTo(array_map(fn ($p) => $p['name'], $permissions));
    }
}

