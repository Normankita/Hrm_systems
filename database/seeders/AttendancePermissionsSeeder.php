<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AttendancePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = array(
            [
                'name' => 'mark_attendance',
                'guard_name' => 'web',
                'group_name' => 'Users_Attendances',
                'slug' => 'mark-attendance',
                'division' => 'group'
            ],
            [
                'name' => 'view_attendances',
                'guard_name' => 'web',
                'group_name' => 'Users_Attendances',
                'slug' => 'Attendance-summary',
                'division' => 'group'
            ],
        );
        // create the actual permissions
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

    }
}

