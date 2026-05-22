<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Http\Services\DailyAttendanceService;
use App\Http\Utils\Traits\AttendanceTrait;
use App\Models\Attendance;
use App\Models\ClosedDay;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminAttendancesController extends Controller
{
    /**
     * Summary of index
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $company = session('company');
        // fetching the weekly attendance chart data
        $attendanceChartData = DailyAttendanceService::getWeeklyChartData();
        $daysOfWeek = $attendanceChartData['daysOfWeek'];
        $presentData = $attendanceChartData['presentData'];
        $absentData = $attendanceChartData['absentData'];
        $lateData = $attendanceChartData['lateData'];

        $today = date('Y-m-d');
        $todayAttendance = DailyAttendanceService::getDayBasedAttendance($today);
        $absentees = DailyAttendanceService::getDayBasedAbsentees($today);
        $presenties = DailyAttendanceService::getDayBasedPresenties($today);
        $lateComers = DailyAttendanceService::getDayBasedLateComers($today);
        $employeesCount = $company->employees()->count();

        return view('admin.attendance.attendance_dashborad')
            ->with('absentees', $absentees->count())
            ->with('presenties', $presenties->count())
            ->with('lateComers', $lateComers->count())
            ->with('employeesCount', $employeesCount)
            ->with('todayAttendance', $todayAttendance)
            ->with('daysOfWeek', $daysOfWeek)
            ->with('presentData', $presentData)
            ->with('absentData', $absentData)
            ->with('lateData', $lateData);
    }


    /**
     * getting the attendance page with all the required details
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function dailyAttendancePage(Request $request)
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

        return view('admin.attendance.daily_attendance', [
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
     * returns the manual entry page
     * @return \Illuminate\Contracts\View\View
     */
  public function manualEntryPage()
{
    $today = Carbon::today();

    // employees who checked in / out today
    $whoAttendToday = Employee::whoAttendToday();       // checked in
    $whoCheckOutToday = Employee::whoCheckoutToday();   // checked out
    $whoCheckOutTodayIds = $whoCheckOutToday->pluck('id');

    // employees who checked in but not checked out
    $forCheckout = $whoAttendToday->whereNull('check_out_time');

    // IDs of employees who have attendance today (not absent)
    $attendedTodayIds = Attendance::whereDate('attendance_date', $today)
        ->where('status', '!=', 'absent')
        ->pluck('employee_id');

    // get active employees:
    //   - those who attended today, OR
    //   - those without attendance
    $employees = Employee::query()
        ->where('state', 'active')
        ->whereNotIn('id', $whoCheckOutTodayIds)
        ->where(function ($q) use ($attendedTodayIds, $today) {
            $q->whereIn('id', $attendedTodayIds) // attended today
              ->orWhereDoesntHave('attendances', function ($q2) use ($today) {
                  $q2->whereDate('attendance_date', $today);
              }); // no attendance today at all
        })
        ->get()
        ->map(function ($employee) use ($attendedTodayIds) {
            // determine intention
            $employee->intend = $attendedTodayIds->contains($employee->id)
                ? 'checkOut'
                : 'checkIn';

            return $employee;
        })
        ->values();

    // get today's full attendance list
    $attendance = DailyAttendanceService::getDayBasedAttendance($today->format('Y-m-d'));

    return view('admin.attendance.manual_entry', [
        'date'            => $today->format('Y-m-d'),
        'todayAttendance' => $attendance,
        'employees'       => $employees,
        'whoAttendToday'  => $whoAttendToday,
        'forCheckout'     => $forCheckout,
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
            $request);
        if ($validate->fails()) {
            return redirect()->back()
                ->with('error',
                'Fail to create attendance record')
                ->withErrors($validate)->withInput();
        }
        $attendance = AttendanceTrait::manualEntryStoreTrait(
            $request);
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


    public function destroy($attendanceId)
    {
        $attendance = AttendanceTrait::deleteAttendance(
            $attendanceId
        );
        if (!$attendance) {
            return redirect()->back()->with(
                'error',
                'Failed to delete attendance record'
            );
        }
        return redirect()->back()->with(
            'success',
            'Attendance record deleted successfully'
        );
    }


    public function update(Request $request, $attendanceId)
    {
        $attendance = AttendanceTrait::updateAttendance($attendanceId,
            $request->all());
        if (!$attendance) {
            return redirect()->back()->with(
                'error',
                'Failed to update attendance record'
            );
        }
        return redirect()->back()->with(
            'success',
            'Attendance record updated successfully'
        );
    }

}
