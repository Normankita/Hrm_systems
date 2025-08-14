<?PHP

namespace App\Http\Services;

use App\Http\Interfaces\AttendanceInterface;
use App\Models\Attendance;

class DailyAttendanceService
{
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

    public static function getDayBasedAttendance($date = null)
    {
        // Logic to retrieve all attendance records based on the date
        if (is_null($date)) {
            return Attendance::all();
        }
        return self::dated($date)
            ->get();
    }

    public function updateAttendance($attendanceId, $data)
    {
        // Logic to update an attendance record
    }

    public static function getDayBasedAbsentees($date = null)
    {
        // Logic to retrieve absentees based on the date, if date is null
        // then just return all absentees
        if (is_null($date)) {
            return Attendance::where('status', 'absent')
                ->get();
        }
        return self::dated($date)
            ->where('status', 'absent')
            ->get();
    }

    public static function getDayBasedPresenties($date = null)
    {
        // Logic to retrieve present employees based on the date
        if (is_null($date)) {
            return Attendance::where('status', 'present')
                ->get();
        }
        return self::dated($date)
            ->where('status', 'present')
            ->get();
    }


    public static function getDayBasedLateComers($date = null)
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
        return Attendance::whereDate('attended_date', $date);
    }
}
