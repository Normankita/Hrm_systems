<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\AttendanceRecord;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-1 year', 'now');
        $checkIn = Carbon::instance($date)->setTime(rand(8, 9), rand(0, 59));
        $checkOut = (clone $checkIn)->addHours(rand(7, 9));

        return [
            'company_id' => 1,
            'employee_id' => 1,
            'check_in_time' => $checkIn->format('H:i:s'),
            'check_out_time' => $checkOut->format('H:i:s'),
            'attendance_date' => $checkIn->toDateString(),
            'status' => $this->faker->randomElement(['Present', 'Absent', 'Late']),
            'remarks' => $this->faker->sentence(),
        ];
    }


    public function configure()
    {
        return $this->afterCreating(function (Attendance $attendance) {
            // Generate 1–3 sessions per attendance
            AttendanceRecord::factory(rand(1, 3))->create([
                'company_id' => $attendance->company_id,
                'employee_id' => $attendance->employee_id,
                'attendance_session_id' => $attendance->id,
                'date' => $attendance->attendance_date,
            ]);
        });
    }
}
