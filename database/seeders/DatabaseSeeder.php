<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call( [
            CompanySeeder::class,
            RolesSeeder::class,
            PermissionSeeder::class,
            AllowancePermissionSeeder::class,
            UserSeeder::class,
            StatusSeeder::class,
            EmployeeStatusHistorySeeder::class,
            EmployeeStatusPermissions::class,
            LoansPermissionSeeder::class,
            OwnerSeeder::class,
        ]);

        $this->call( LeaveTypeSeeder::class);
        $this->call( AddingPaygradeToDefaultEmployeeSeeder::class );
        $this->call( AttendancePermissionsSeeder::class );
        $this->call( SettingsOptionsSeeder::class );
        $this->call( AttencanceIndividualPermissionSeeder::class);
        $this->call(CompanyIdInjectSeeder::class);
        // --- developemnt seeders --
        // seeder for factory, remove in production
        $this->call( AttendanceSeeder::class );

    }
}
