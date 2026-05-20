<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ContractPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'view_contracts',
                'guard_name' => 'web',
                'group_name' => 'contracts',
                'slug' => 'view-employee-contracts',
                'division' => 'group',
            ],
             [
                'name' => 'edit_contracts',
                'guard_name' => 'web',
                'group_name' => 'contracts',
                'slug' => 'edit-employee-contracts',
                'division' => 'group',
            ],
             [
                'name' => 'download_contracts',
                'guard_name' => 'web',
                'group_name' => 'contracts',
                'slug' => 'download-employee-contracts',
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
