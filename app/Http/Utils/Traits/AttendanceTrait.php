<?php
namespace App\Http\Utils\Traits;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait AttendanceTrait
{

    // This trait can be used to handle attendance-related functionalities
    // such as creating attendance records, managing attendance sessions, etc.

    // Example method to create an attendance record
    public static function createAttendanceRecord($data)
    {

    }

    public static function getEmployeeAttendanceRecords($employeeId, $date = null)
    {

    }

    public static function getAllAttendanceRecords($date = null)
    {

    }

    public function updateAttendanceRecord($attendanceId, $data)
    {

    }

    public static function getDayBasedAbseties($date = null)
    {
        // Logic to retrieve absentees based on the date

    }

    public static function getChart_7days()
    {
        // Get start and end of the current week
        $startFrom = Carbon::now()->subDays(7); // Monday
        $endIn = Carbon::now();   // Sunday
        return self::getChartData($startFrom, $endIn);
    }

  private static function getChartData($startFrom, $endIn)
{
    return Attendance::select(
            DB::raw('DAYNAME(attendance_date) as day'),
            DB::raw("SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as presentCount"),
            DB::raw("SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absentCount")
        )
        ->whereBetween('attendance_date', [$startFrom, $endIn])
        ->groupBy('day')
        ->orderByRaw("FIELD(day, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
        ->get();
}

}
