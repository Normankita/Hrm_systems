<?php
namespace App\Http\Services;

use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Payroll;
use Carbon\Carbon;

class DashboardDataService
{

    public static function getEmployeeDashboardData($employee)
    {
        $spentLeaves = $employee->getSpentLeaves();
        $leaveDays = session()->get('leave_days');
        $leaveDaysCount = 0;
        if ($spentLeaves) {
            $leaveDaysCount = self::getLeaveDaysCount($spentLeaves);
        }
        $leavebalance = $leaveDays - $leaveDaysCount;
        $data = [];
        if ($employee) {
            $data['net_salary'] = $employee->getApprovedMonthPayrolls(Carbon::now());
            $data['total_payrolls'] = $employee->payrolls()->count();
            $data['total_deductions'] = $employee->deductions()->count();
            $data['total_leaves'] = $employee->leaves()->count();
            $data['leave_balance'] = $leavebalance;
            $data['recent_leaves'] = $employee->leaves()->orderBy('created_at', 'desc')->take(5)->get();
            $data['recent_payrolls'] = $employee->payrolls()->orderBy('created_at', 'desc')->take(5)->get();
        }
        return $data;

    }


    public static function getAdminDashboardData()
    {
        $data = [];

        $data['total_employees'] = Employee::count();
        $data['employees_on_leave'] = Employee::countEmployeesCurrentlyOnLeave();
        $data['number_payrolls'] = Payroll::where('status', 'approved')->count();
        $data['total_payrolls'] = Payroll::where('status', 'approved')->sum('net_salary');
        $data['total_deductions'] = Deduction::sum('total_amount');
        $data['recent_employees'] = Employee::orderBy('created_at', 'desc')->take(5)->get();

        dd($data);
    }


    /**
     * Calculate the total number of leave days taken by the employee.
     */

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