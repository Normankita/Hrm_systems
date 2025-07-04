<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class LoansPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $loanPermissions = array(
            [
                'name' => 'view_loans',
                'guard_name' => 'web',
                'group_name' => 'loans',
                'slug' => 'view-all-loans',
                'division' => 'group'
            ],
            [
                'name' => 'create_loans',
                'guard_name' => 'web',
                'group_name' => 'loans',
                'slug' => 'create-any-loan',
                'division' => 'group'
            ],
            [
                'name' => 'edit_loans',
                'guard_name' => 'web',
                'group_name' => 'loans',
                'slug' => 'edit-any-loan',
                'division' => 'group'
            ],
            [
                'name' => 'delete_loans',
                'guard_name' => 'web',
                'group_name' => 'loans',
                'slug' => 'delete-any-loan',
                'division' => 'group'
            ],
        );
        $adminRole = Role::findByName('ADMIN');

        foreach ($loanPermissions as $permission) {
            Permission::create($permission);
            $adminRole->givePermissionTo($permission['name']);
        }
    }
}
