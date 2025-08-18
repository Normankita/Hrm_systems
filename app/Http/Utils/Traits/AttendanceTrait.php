<?php
namespace App\Http\Utils\Traits;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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

    public static function manualEntryValidation($request)
    {
        // custome message for validation rule i made
        $message = [
            'employee_id.required' => 'Employee ID is required.',
            'employee_id.exists' => 'The selected employee does not exist.',
            'date.required' => 'Attendance date is required.',
            'date.date' => 'The attendance date must be a valid date.',
            'date.unique' => 'An attendance record for this employee on this date already exists.',
            'status.required' => 'Attendance status is required.',
            'status.in' => 'The selected status is invalid.',
            'remarks.string' => 'Remarks must be a string.',
            'remarks.max' => 'Remarks may not be greater than 255 characters.'
        ];
        return validator($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'date' => ['required', 'date', Rule::unique('attendances', 'attendance_date')->where(function ($query) use ($request) {
                return $query->where('employee_id', $request->employee_id)
                    ->whereDate('attendance_date', $request->date);
            })],
            'status' => 'required|in:present,absent,late,leave',
            'remarks' => 'nullable|string|max:255',
        ], $message);
    }

    public static function manualEntryStore($request)
    {
        $attendance = new Attendance();
        $attendance->employee_id = $request->employee_id;
        $attendance->attendance_date = $request->date;
        $attendance->status = $request->status;
        $attendance->check_in_time = $request->check_in;
        $attendance->check_out_time = $request->check_out;
        $attendance->remarks = $request->remarks;

        return $attendance->save() ? $attendance : null;
    }
}
