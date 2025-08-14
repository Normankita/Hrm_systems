<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-1 year', 'now'); // Random date within last year
        $checkIn = Carbon::instance($date)->setTime(rand(8, 9), rand(0, 59)); // Between 8:00 - 9:59
        $checkOut = (clone $checkIn)->addHours(rand(7, 9)); // 7-9 hours after check-in

        return [
            'company_id'     => 1, // Assuming company_id 1 for factory 
            'employee_id'     => 1,
            'check_in_time'   => $checkIn->format('H:i:s'),
            'check_out_time'  => $checkOut->format('H:i:s'),
            'attendance_date' => $checkIn->toDateString(),
            'status'          => $this->faker->randomElement(['Present', 'Absent', 'Late']),
            'remarks'         => $this->faker->sentence(),
        ];
    }
}
