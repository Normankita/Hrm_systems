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
            ['name' => 'Probation', 'color' => 'blue', 'description' => 'Employee is on probation period'],
            ['name' => 'Retired', 'color' => 'purple', 'description' => 'Employee has retired'],
            ['name' => 'Contracted', 'color' => 'orange', 'description' => 'Employee is on a contract basis'],
            ['name' => 'Resigned', 'color' => 'pink', 'description' => 'Employee is an Resigned'],
            
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
