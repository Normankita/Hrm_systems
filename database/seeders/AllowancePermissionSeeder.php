<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AllowancePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $allowancePermissions = array(
            [
                'name' => 'view_allowances',
                'guard_name' => 'web',
                'group_name' => 'allowances',
                'slug' => 'view-all-allowances',
                'division' => 'group'
            ],
            [
                'name' => 'create_allowances',
                'guard_name' => 'web',
                'group_name' => 'allowances',
                'slug' => 'create-any-allowance',
                'division' => 'group'
            ],
            [
                'name' => 'edit_allowances',
                'guard_name' => 'web',
                'group_name' => 'allowances',
                'slug' => 'edit-any-allowance',
                'division' => 'group'
            ],
            [
                'name' => 'delete_allowances',
                'guard_name' => 'web',
                'group_name' => 'allowances',
                'slug' => 'delete-any-allowance',
                'division' => 'group'
            ],
        );
        $adminRole = Role::findByName('ADMIN');

        foreach ($allowancePermissions as $permission) {
            Permission::create($permission);
            $adminRole->givePermissionTo($permission['name']);
        }
    }
}