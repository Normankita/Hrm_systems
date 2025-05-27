<?php

namespace App\Http\Utils\Traits;

use App\Models\Employee;
use Carbon\Carbon;
use PhpParser\Node\Stmt\Return_;

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
                'message' => 'You are not eligible to request a leave.'
            ];
        }
        return [
            'status' => 'success',
            'message' => 'You are eligible to request a leave.'
        ];
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
