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
            ],
            [
                'name' => 'create_employees',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit_employees',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete_employees',
                'guard_name' => 'web',
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
