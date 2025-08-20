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
        $data = [
            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'status' => $request->status,
            'remarks' => $request->remarks,
        ];
        return validator($data, [
            'employee_id' => 'required|exists:employees,id',
            'date' => [
                'required',
                'date',
                Rule::unique('attendances', 'attendance_date')->where(function ($query) use ($request) {
                    return $query->where('employee_id', $request->employee_id)
                        ->whereDate('attendance_date', $request->date)
                        ->where('deleted_at', null);
                })
            ],
            'status' => 'required|in:present,absent,late,leave',
            'remarks' => 'nullable|string|max:255',
        ], $message);
    }

    public static function manualEntryStoreTrait($request)
    {
        $attendance = new Attendance();
        $attendance->employee_id = $request->employee_id;
        $attendance->attendance_date = $request->date;
        $attendance->status = $request->status ?? 'present';
        $attendance->check_in_time = $request->check_in ?? null;
        $attendance->check_out_time = $request->check_out ?? null;
        $attendance->remarks = $request->remarks ?? null;

        // if attendance is not present or late the checkin and out are always ''
        if ($attendance->status !== 'present' && $attendance->status !== 'late') {
            $attendance->check_in_time = null;
            $attendance->check_out_time = null;
        }
        return $attendance->save() ? $attendance : null;
    }


    public static function manualCheckOutTrait($request)
    {
        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('attendance_date', $request->date)
            ->first();

        if (!$attendance) {
            return null; // No attendance record found for the employee on the given date
        }

        $attendance->check_out_time = $request->check_out ?? now()->format('H:i:s');
    }


    public static function deleteAttendance($attendanceId)
    {
        $attendance = Attendance::find($attendanceId);
        if (!$attendance) {
            return response()->json(['error' => 'Attendance record not found'], 404);
        }
        $attendance->delete();
        return response()->json(['success' => 'Attendance record deleted successfully'], 200);
    }


    public static function updateAttendance($attendanceId, $data)
    {
        $attendance = Attendance::find($attendanceId);
        if (!$attendance) {
            return response()->json(['error' => 'Attendance record not found'], 404);
        }
        $data = [
            'check_in_time' => $data['check_in'] ?? null,
            'check_out_time' => $data['check_out'] ?? null,
            'status' => $data['status'] ?? 'present',
            'remarks' => $data['remarks'] ?? null,
        ];
        if ($data['status'] !== 'present' && $data['status'] !== 'late') {
            $data['check_in_time'] = null;
            $data['check_out_time'] = null;
        } else {
            if (empty($data['check_in']) || is_null($data['check_in'])) {
                $data['check_in_time'] = now()->format('H:i:s');
            }
        }
        $attendance->update($data);
        return response()->json(['success' => 'Attendance record updated successfully'], 200);
    }


}
