<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PayGrade;

class PayGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payGrades = [
            [
                'name' => 'Junior',
                'base_salary' => 30000,
                'max_salary' => 40000,
                'base_month_count' => 12,
                'description' => 'Entry-level pay grade for new employees.',
            ],
            [
                'name' => 'Mid',
                'base_salary' => 40001,
                'max_salary' => 60000,
                'base_month_count' => 12,
                'description' => 'Mid-level pay grade for experienced employees.',
            ],
            [
                'name' => 'Senior',
                'base_salary' => 60001,
                'max_salary' => 90000,
                'base_month_count' => 12,
                'description' => 'Senior-level pay grade for highly experienced employees.',
            ],
        ];

        foreach ($payGrades as $grade) {
            PayGrade::create($grade);
        }
    }
}