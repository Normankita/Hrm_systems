<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeStatusHistory;
use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeStatusHistorySeeder extends Seeder
{
    public function run()
    {
        // Fetch some employees and statuses
        // $employees = Employee::take(10)->get(); this is a really cool code snippet to get 10 employees
        $employees = Employee::all();
        $statuses = Status::all()->keyBy('name');

        foreach ($employees as $employee) {
            // Assign an initial status
            EmployeeStatusHistory::create([
                'employee_id' => $employee->id,
                'status_id' => $statuses['Active']->id,
                'effective_date' => now()->subMonths(3),
                'reason' => 'Initial employment',
            ]);

            // Randomly add another status
            if (rand(0, 1)) {
                EmployeeStatusHistory::create([
                    'employee_id' => $employee->id,
                    'status_id' => $statuses['Suspended']->id,
                    'effective_date' => now()->subMonth(),
                    'reason' => 'Annual leave',
                ]);
            }
        }
    }
}