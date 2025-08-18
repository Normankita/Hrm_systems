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
        return self::dated($date)
            ->where('status', 'absent')
            ->get();
    }

    public static function getDayBasedPresenties($date)
    {
        return self::dated($date)
            ->where('status', 'present')
            ->get();
    }


    public static function getDayBasedLateComers($date)
    {
        // Logic to retrieve late comers based on the date
        if (is_null($date)) {
            return Attendance::where('status', 'late')
                ->get();
        }
        return self::dated($date)
            ->where('status', 'late')
            ->get();
    }

    private static function dated($date)
    {
        // Helper function to format the date
        return Attendance::whereDate('attendance_date', $date)
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
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $presentData = [];
        $absentData = [];
        foreach ($daysOfWeek as $day) {
            $match = $chart_7days->firstWhere('day', $day);
            $presentData[] = $match ? $match->presentCount : 0;
            $absentData[] = $match ? $match->absentCount : 0;
        }
        return [
            'daysOfWeek' => $daysOfWeek,
            'presentData' => $presentData,
            'absentData' => $absentData,
        ];
    }
}
