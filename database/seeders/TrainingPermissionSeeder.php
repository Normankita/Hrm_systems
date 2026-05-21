<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TrainingPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'view_trainings',
                'guard_name' => 'web',
                'group_name' => 'trainings',
                'slug' => 'view-trainings',
                'division' => 'group',
            ],
            [
                'name' => 'manage_trainings',
                'guard_name' => 'web',
                'group_name' => 'trainings',
                'slug' => 'manage-trainings',
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
