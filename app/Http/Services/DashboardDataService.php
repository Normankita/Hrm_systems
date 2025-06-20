<?php
namespace App\Http\Services;

use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Payroll;
use App\Models\Setting;
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
            $data['recent_leave_request'] = Employee::getRecentLeaveRequest();
            $data['leave_types'] = LeaveType::all();
            $data['employees_on_leave'] = Employee::countEmployeesCurrentlyOnLeave();
            $data['total_employees'] = Employee::count();


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
        $data['recent_leaves'] = Leave::orderBy('created_at', 'desc')->take(5)->get();
        $data['pending_payrolls'] = Payroll::where('status', 'pending')->count();
        $data['last_payroll_period'] = Payroll::latest()->first() ? Payroll::latest()->first()->created_at->format('F j') : null;

        $pendingPayroll = Payroll::where('status', 'pending')->first();

        if ($pendingPayroll) {
            $paymentDateSetting = Setting::where('name', 'payment_date')->first();

            if ($paymentDateSetting) {
                $dayOfMonth = intval($paymentDateSetting->value);
                // Get the target payment date in the current month
                $now = Carbon::now();
                $year = $now->year;
                $month = $now->month;

                // If today is past the payment day, use next month
                if ($now->day > $dayOfMonth) {
                    $paymentDate = Carbon::createFromDate($year, $month, 1)->addMonth()->day($dayOfMonth);
                } else {
                    $paymentDate = Carbon::createFromDate($year, $month, 1)->day($dayOfMonth);
                }

                $data['days_left_for_payment'] = $now->diffInDays($paymentDate, false);
            } else {
                $data['days_left_for_payment'] = null;
            }
        } else {
            $data['days_left_for_payment'] = null;
        }



        return $data;
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