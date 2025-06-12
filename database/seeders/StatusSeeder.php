<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Active', 'color' => 'green', 'description' => 'Employee is actively working'],
            ['name' => 'Suspended', 'color' => 'red', 'description' => 'Employee is suspended'],
            ['name' => 'On Leave', 'color' => 'yellow', 'description' => 'Employee is on leave'],
            ['name' => 'Terminated', 'color' => 'gray', 'description' => 'Employment ended'],
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
