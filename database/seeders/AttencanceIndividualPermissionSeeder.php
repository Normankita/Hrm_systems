<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AttencanceIndividualPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = array(
            [
                'name' => 'ind_mark_attendance',
                'guard_name' => 'web',
                'group_name' => 'Individual_Attendance',
                'slug' => 'mark-my-attendance',
                'division' => 'individual'
            ],
            [
                'name' => 'ind_view_attendance',
                'guard_name' => 'web',
                'group_name' => 'Individual_Attendance',
                'slug' => 'Attendings',
                'division' => 'individual'
            ],
        );
        // create the actual permissions
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

    }
}
