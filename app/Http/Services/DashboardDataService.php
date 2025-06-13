<?php
namespace App\Http\Services;

use App\Models\Employee;
use Carbon\Carbon;

class DashboardDataService
{

    public static function getEmployeeDashboardData($employee)
    {
        $spentLeaves=$employee->getSpentLeaves();
        $leaveDays = session()->get('leave_days');
        $leaveDaysCount = 0;
        if ($spentLeaves) {
            $leaveDaysCount =self::getLeaveDaysCount($spentLeaves);
        }
        $leavebalance= $leaveDays - $leaveDaysCount;
        $data = [];
        if ($employee) {
            $data['net_salary']=$employee->getApprovedMonthPayrolls(Carbon::now());
            $data['total_payrolls'] = $employee->payrolls()->count();
            $data['total_deductions'] = $employee->deductions()->count();
            $data['total_leaves'] = $employee->leaves()->count();
            $data['leave_balance'] = $leavebalance;
            $data['recent_leaves'] = $employee->leaves()->orderBy('created_at', 'desc')->take(5)->get();
            $data['recent_payrolls'] = $employee->payrolls()->orderBy('created_at', 'desc')->take(5)->get();
        }
        return $data;

    }

        private static function getLeaveDaysCount($employeeLeaves)
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