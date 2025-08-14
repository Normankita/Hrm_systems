<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Http\Services\DailyAttendanceService;
use App\Http\Utils\Traits\AttendanceTrait;
use Illuminate\Http\Request;

class AdminAttendancesController extends Controller
{
    use AttendanceTrait;

    /**
     * Summary of index
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $company = session('company');
        $today = date('Y-m-d');
        // chart details starts
        $chart_7days = self::getChart_7days();

        // Make sure all days are present
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $presentData = [];
        $absentData = [];
        foreach ($daysOfWeek as $day) {
            $match = $chart_7days->firstWhere('day', $day);
            $presentData[] = $match ? $match->presentCount : 0;
            $absentData[] = $match ? $match->absentCount : 0;
        }

        $todayAttendance = DailyAttendanceService::getDayBasedAttendance();
        $absentees = DailyAttendanceService::getDayBasedAbsentees();
        $presenties = DailyAttendanceService::getDayBasedPresenties();
        $lateComers = DailyAttendanceService::getDayBasedLateComers();
        $employeesCount = $company->employees()->count();
        return view('admin.attendance.attendance_dashborad')
            ->with('absentees', $absentees->count())
            ->with('presenties', $presenties->count())
            ->with('lateComers', $lateComers->count())
            ->with('employeesCount', $employeesCount)
            ->with('todayAttendance', $todayAttendance)
            ->with('daysOfWeek', $daysOfWeek)
            ->with('presentData', $presentData)
            ->with('absentData', $absentData);
    }

}
