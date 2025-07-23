<?php

namespace App\Http\Utils\Traits;

use App\Models\Employee;
use Carbon\Carbon;

trait LeaveTrait
{

    /**
     * Summary of checkEligibility
     * checking if the employee is qualified to be granted a leave
     * @param mixed $employee
     * @return array{message: string, status: string}
     */
    public function checkEligibility(Employee $employee)
    {
        // checking if the user in eligible for requesting a leave
        $leaveDays = session()->get('leave_days');
        $employeeLeaves = $employee->getSpentLeaves();

        $daysCount = $this->getLeaveDaysCount($employeeLeaves);
        if ($daysCount >= $leaveDays) {
            return [
                'status' => 'fail',
                'message' => 'You are not eligible to request a leave. Your Days Count: ' . $daysCount . ' exceeds the allowed limit of ' . $leaveDays . '.'
            ];
        }
        return [
            'status' => 'success',
            'message' => 'You are eligible to request a leave.'
        ];
    }


    public static function getStaticLeaveDaysCount($employeeLeaves)
    {
        $daysCount = 0;
        // calculate number of days
        foreach ($employeeLeaves as $leave) {
            $startDate = Carbon::parse($leave->start_date);
            $endDate = Carbon::parse($leave->end_date);
            // calculate the number of days between the two dates
            $days = ($startDate->diffInDays($endDate));
            $daysCount += $days;
        }
        return $daysCount;
    }


    /**
     * Summary of getLeaveDaysCount
     * Calculating the number of days an employee has taken leave
     * @param mixed $employeeLeaves
     * @return float|int
     */
    private function getLeaveDaysCount($employeeLeaves)
    {
        $daysCount = 0;
        // calculate number of days
        foreach ($employeeLeaves as $leave) {
            $startDate = Carbon::parse($leave->start_date);
            $endDate = Carbon::parse($leave->end_date);
            // calculate the number of days between the two dates
            $days = ($startDate->diffInDays($endDate));
            $daysCount += $days;
        }
        return $daysCount;
    }

}
