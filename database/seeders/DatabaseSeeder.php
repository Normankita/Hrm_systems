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
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call( [
            CompanySeeder::class,
            RolesSeeder::class,
            PermissionSeeder::class,
            AllowancePermissionSeeder::class,
            UserSeeder::class,
            StatusSeeder::class,
            EmployeeStatusHistorySeeder::class,
        ]);
        $this->call( LeaveTypeSeeder::class );
        $this->call( AddingPaygradeToDefaultEmployeeSeeder::class );

    }
}
