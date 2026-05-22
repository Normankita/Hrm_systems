<?PHP

namespace App\Http\Services;

use App\Http\Interfaces\AttendanceInterface;
use App\Http\Utils\Traits\AttendanceTrait;
use App\Models\Attendance;

class DailyAttendanceService
{
    use AttendanceTrait;

    // This service can be used to handle daily attendance logic,
    // such as processing daily attendance records, generating reports, etc.

    public static function createAttendanceRecord($data)
    {
        // Logic to create an attendance record
    }

    public static function getEmployeeAttendanceRecords($employeeId, $date = null)
    {
        // Logic to retrieve attendance records for a specific employee
    }

    public static function getDayBasedAttendance($date)
    {
        return self::dated($date)
            ->get();
    }

    public function updateAttendance($attendanceId, $data)
    {
        // Logic to update an attendance record
    }

    public static function getDayBasedAbsentees($date)
    {
        $return = self::dated($date)
            ->get();
        return $return->where('status', 'absent');
    }

    public static function getDayBasedPresenties($date)
    {
        $return = self::dated($date)
            ->get();
        return $return->whereIn('status', ['present', 'late']);
    }


    public static function getDayBasedLateComers($date)
    {
        // Logic to retrieve late comers based on the date
        if (is_null($date)) {
            return Attendance::where('status', 'late')
                ->get();
        }
        $response = self::dated($date)
            ->get();
        return $response->where('status', 'late');
    }

    private static function dated($date)
    {
        return Attendance::where('attendance_date', 'like', $date . '%') // for datetime/timestamp columns
            ->orWhereDate('attendance_date', $date) // fallback for date columns
            ->orderBy('id', 'desc')
            ->with('employee');
    }

    public static function getWeeklyChartData()
    {
        $company = session('company');
        $today = date('Y-m-d');
        // chart details starts
        $chart_7days = self::getChart_7days();

        // Make sure all days are present
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday',
            'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $presentData = [];
        $absentData = [];
        foreach ($daysOfWeek as $day) {
            $match = $chart_7days->firstWhere('day', $day);
            $presentData[] = $match ? $match->presentCount : 0;
            $absentData[] = $match ? $match->absentCount : 0;
            $lateData[] = $match ? $match->lateCount : 0;
        }
        return [
            'daysOfWeek' => $daysOfWeek,
            'presentData' => $presentData,
            'absentData' => $absentData,
            'lateData' => $lateData
        ];
    }
}
