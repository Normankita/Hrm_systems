<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttendanceRecord>
 */
class AttendanceRecordFactory extends Factory
{
    public function definition(): array
    {
        $checkIn = $this->faker->dateTimeBetween('08:00:00', '12:00:00');
        $checkOut = (clone $checkIn)->modify('+'.rand(1, 4).' hours');

        return [
            'company_id'            => 1,
            'employee_id'           => 1,
            'attendance_session_id' => null, // will link later
            'date'                  => Carbon::instance($checkIn)->toDateString(),
            'status'                => $this->faker->randomElement(['Present', 'Late']),
            'check_in'              => Carbon::instance($checkIn)->format('H:i:s'),
            'check_out'             => Carbon::instance($checkOut)->format('H:i:s'),
            'remarks'               => $this->faker->sentence(),
        ];
    }
}

