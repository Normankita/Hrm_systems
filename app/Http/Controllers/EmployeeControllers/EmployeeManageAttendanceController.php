<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Services\DailyAttendanceService;
use App\Http\Utils\Traits\AttendanceTrait;
use App\Models\ClosedDay;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeManageAttendanceController extends Controller
{
    public function dashboard()
    {
        $company = session('company');
        // fetching the weekly attendance chart data
        $attendanceChartData = DailyAttendanceService::getWeeklyChartData();
        $daysOfWeek = $attendanceChartData['daysOfWeek'];
        $presentData = $attendanceChartData['presentData'];
        $absentData = $attendanceChartData['absentData'];

        $today = date('Y-m-d');
        $todayAttendance = DailyAttendanceService::getDayBasedAttendance($today);
        $absentees = DailyAttendanceService::getDayBasedAbsentees($today);
        $presenties = DailyAttendanceService::getDayBasedPresenties($today);
        $lateComers = DailyAttendanceService::getDayBasedLateComers($today);
        $employeesCount = $company->employees()->count();
        return view('employee.manage.attendance.attendance_dashboard')
            ->with('absentees', $absentees->count())
            ->with('presenties', $presenties->count())
            ->with('lateComers', $lateComers->count())
            ->with('employeesCount', $employeesCount)
            ->with('todayAttendance', $todayAttendance)
            ->with('daysOfWeek', $daysOfWeek)
            ->with('presentData', $presentData)
            ->with('absentData', $absentData);
    }


    public function manualEntryPage()
    {
        $whoAttendToday = Employee::whoAttendToday();
        $whoCheckOutToday = Employee::whoCheckoutToday();
        $whoCheckOutTodayIds = $whoCheckOutToday->pluck('id');
        $forCheckout = Employee::whoAttendToday()
            ->where('check_out_time', null);
        $employees = Employee::where('state', 'active')
            ->get()
            ->map(function ($employee) use ($whoAttendToday, $whoCheckOutTodayIds) {
                if ($whoCheckOutTodayIds->contains($employee->id)) {
                    return null;
                }
                $employee->intend = !$whoAttendToday->contains($employee) ? 'checkIn' : 'checkOut';
                return $employee;
            })->filter()->values();
        $getByDate = Carbon::now()->format('Y-m-d');
        $attendance = DailyAttendanceService::getDayBasedAttendance($getByDate);
        return view('employee.manage.attendance.manual_entry', [
            'date' => $getByDate,
            'todayAttendance' => $attendance,
            'employees' => $employees,
            'whoAttendToday' => $whoAttendToday,
            'forCheckout' => $forCheckout
        ]);
    }


    public function dailyAttendance(Request $request)
    {
        $getByDate = $request->get('date');
        if (!$getByDate) {
            $getByDate = Carbon::now()->format('Y-m-d');
        }
        $attendanceDetails = DailyAttendanceService::getDayBasedAttendance(
            $getByDate
        );
        // apply the other remaining filters
        $getByDepartment = strtolower($request->get('department'));
        $status = $request->get('status');
        if ($getByDepartment && strtolower($getByDepartment) !== 'all') {
            $attendanceDetails = $attendanceDetails->where(
                'employee.department_id',
                $getByDepartment
            );
        }
        if ($status && strtolower($status) !== 'all') {
            $attendanceDetails = $attendanceDetails->where('status', $status);
        }
        // fetching the closing status of the current date
        $closed = ClosedDay::whereDate('closed_date', 'like', $getByDate)
            ->exists();
        // Extra details to send to view page
        $employees = session('company')->employees;
        $present = DailyAttendanceService::getDayBasedPresenties($getByDate);
        $absent = DailyAttendanceService::getDayBasedAbsentees($getByDate);
        $late = DailyAttendanceService::getDayBasedLateComers($getByDate);

        return view('employee.manage.attendance.daily_attendance', [
            'attendanceDetails' => $attendanceDetails,
            'date' => $getByDate,
            'departments' => session('company')->departments()->get(),
            'selectedStatus' => $status,
            'selectedDepartment' => $getByDepartment,
            'employees' => $employees,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'isClosed' => $closed
        ]);
    }


    /**
     * for storing
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function manualEntryStore(Request $request)
    {
        $validate = AttendanceTrait::manualEntryValidation(
            $request
        );
        if ($validate->fails()) {
            return redirect()->back()
                ->with(
                    'error',
                    'Fail to create attendance record'
                )
                ->withErrors($validate)->withInput();
        }
        $attendance = AttendanceTrait::manualEntryStoreTrait(
            $request
        );
        if (!$attendance) {
            return redirect()->back()->with(
                'error',
                'Failed to create attendance record'
            );
        }
        return redirect()->back()->with(
            'success',
            'Attendance record created successfully'
        );
    }

}
