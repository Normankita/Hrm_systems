<?php
namespace App\Http\Utils\Traits;

use App\Models\Attendance;
use App\Models\AttendanceRecord;
use App\Models\ClosedDay;
use App\Models\Employee;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Throwable;

trait AttendanceTrait
{
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
            DB::raw("SUM(CASE WHEN status IN ('present', 'late') THEN 1 ELSE 0 END) as presentCount"),
            DB::raw("SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absentCount"),
            DB::raw("SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as lateCount")
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
            'type' => $request->type,
            'status' => $request->status,
            'remarks' => $request->remarks,
        ];
        return validator($data, [
            'employee_id' => 'required|exists:employees,id',
            'type' => 'nullable|in:check_in,check_out',
            'date' => [
                'required',
                'date',
                $request->type != 'check_out' ? Rule::unique('attendances', 'attendance_date')->where(
                    function ($query) use ($request) {
                        return $query->where('employee_id', $request->employee_id)
                            ->whereDate('attendance_date', $request->date)
                            ->where('deleted_at', null);
                    }
                ) : null
            ],
            // 'status' => 'required|in:present,absent,late,leave',
            'remarks' => 'nullable|string|max:255',
        ], $message);
    }



    public static function manualEntryStoreTrait($request)
    {
        if ($request->type && $request->type == 'check_out') {
            return self::manualCheckOutTrait($request);
        }
        $attendance = [];
        $attendance['employee_id'] = $request->employee_id;
        $attendance['attendance_date'] = $request->date;
        $attendance['status'] = $request->status ?? 'present';
        $attendance['check_in_time'] = $request->check_in_time ?? null;
        $attendance['check_out_time'] = $request->check_out_time ?? null;
        $attendance['remarks'] = $request->remarks ?? null;

        // fetching employee session start time for default check in time
        $inTime = self::employeeInTime($request->employee_id);
        $attendance['check_in_time'] = $attendance['check_in_time'] ?? $inTime;
        // if attendance is not present or late the checkin and out are always ''
        if ($attendance['status'] !== 'present' && $attendance['status'] !== 'late') {
            $attendance['check_in_time'] = null;
            $attendance['check_out_time'] = null;
        }
        if (
            ($attendance['status'] == 'present' || $attendance['status'] == 'late')
            && empty($attendance['check_in_time'])
        ) {
            $attendance['check_in_time'] = now()->format('H:i:s');
        }
        $attendance = self::createAttendance($attendance);
        return $attendance;
    }


    private static function createAttendance($details)
    {
        $employee = Employee::find($details['employee_id']);
        if (!$employee) {
            return null;
        }
        DB::beginTransaction();
        try {
            $attendance = Attendance::create($details);
            if ($details['status'] == "present" || $details['status'] == "late") {
                AttendanceRecord::create([
                    'employee_id' => $details['employee_id'],
                    'attendance_session_id' => $employee->attendance_session_id,
                    'date' => isset($details['attendance_date']) ?
                        $details['attendance_date'] : Carbon::now()->format('Y-m-d'),
                    'status' => $details['status'],
                    'check_in' => $details['check_in_time'],
                    'check_out' => $details['check_out_time'],
                    'remarks' => $details['remarks'],
                ]);
            }
            DB::commit();
            return $attendance;
        } catch (Throwable $throwable) {
            Log::info('Error creating attendance: ' . $throwable->getMessage());
            DB::rollBack();
            return null;
        }
    }


    public static function outTime($employeeId)
    {
        $employee = Employee::where('id', $employeeId)
            ->with('attendanceSession')
            ->first();
        $companyDefaultDefaultShift = $employee && $employee->company
            ? $employee->company->defaultShift()
            : null;
        $outTime = $employee && $employee->attendanceSession
            ? Carbon::parse($employee->attendanceSession->end_time)
            : ($companyDefaultDefaultShift ? Carbon::parse($companyDefaultDefaultShift->end_time)
                : null);
        if (!$outTime) {
            throw new \Exception('Out time not found');
        }
        return $outTime->format('H:i:s');
    }


    public static function isLate($employeeId = null, $comparingTime = null)
    {
        $employeeId = $employeeId ?? auth()->user()->employee->id;
        $inTime = self::employeeInTime($employeeId);
                $comparingTime = $comparingTime ?? now();

        $inTime = Carbon::parse($inTime);
        $comparingTime = Carbon::parse($comparingTime);
        return $comparingTime->greaterThan($inTime);
    }

    public static function employeeInTime($employeeId)
    {
        $employee = Employee::where('id', $employeeId)
            ->with('attendanceSession')
            ->with('company')
            ->first();
        $companyDefaultDefaultShift = $employee && $employee->company
            ? $employee->company->defaultShift()
            : null;
        $inTime = $employee && $employee->attendanceSession
            ? Carbon::parse($employee->attendanceSession->start_time)
            : ($companyDefaultDefaultShift ? Carbon::parse($companyDefaultDefaultShift->start_time)
                : Carbon::now());
        return $inTime->format('H:i:s');
    }


    public static function manualCheckOutTrait($request)
    {
        $attendance = Attendance::where(
            'employee_id',
            $request->employee_id
        )
            ->whereDate('attendance_date', $request->date)
            ->first();
        if (!$attendance) {
            return null; // No attendance record found for the employee on the given date
        }
        // fetching the employee from attendance
        $outTime = self::outTime($request->employee_id);
        $attendance->check_out_time = $request->check_out_time ?? $outTime;
        // $attendance
        $attendance->save();
        return $attendance;
    }



    public static function deleteAttendance($attendanceId)
    {
        $attendance = Attendance::find($attendanceId);
        if (!$attendance) {
            return response()->json(
                ['error' => 'Attendance record not found'],
                404
            );
        }
        $attendance->delete();
        return response()->json(
            ['success' => 'Attendance record deleted successfully'],
            200
        );
    }


    public static function getStateAndTime($employeeId, $comparingTime = null)
    {
        $EmpInTime = self::employeeInTime($employeeId);
        if (!$comparingTime || empty($comparingTime)) {
            $time = $EmpInTime;
            $state = 'present';
        } else {
            $isLate = self::isLate($employeeId, $comparingTime);
            if ($isLate) {
                $state = 'late';
            } else {
                $state = 'present';
            }
            $time = $comparingTime;
        }
        return ['state' => $state, 'time' => $time];
    }



    public static function updateAttendance($attendanceId, $data)
    {
        $attendance = Attendance::find($attendanceId);
        if (!$attendance) {
            return response()->json(
                ['error' => 'Attendance record not found'],
                404
            );
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
            if (empty($data['check_in_time']) || is_null($data['check_in_time'])) {
                $data['check_in_time'] = now()->format('H:i:s');
            }
        }
        $attendance->update($data);
        return response()->json(
            ['success' => 'Attendance record updated successfully'],
            200
        );
    }


    public static function closeAttendanceForTheDay(
        $date,
        $company_id
    ) {
        // fetch user qualified to be filled in attendance
        $employees = Employee::whereDoesntHave('attendances', function ($query) use ($date) {
            $query->whereDate('attendance_date', 'like', $date . "%");
        })
            ->where('state', 'active')
            ->where('userStatus', 1)
            ->get();
        // mark them all as absentees
        DB::beginTransaction();
        try {
            // checking if the date is already closed
            $attendance = ClosedDay::whereDate('closed_date', 'like', $date . "%")
                ->first();
            if ($attendance) {
                // change status to true
                $attendance->update([
                    'is_active' => false
                ]);
                DB::commit();
                return ['status' => 'success'];
            }
            // otherwise close the day
            $closedDay = ClosedDay::create([
                'company_id' => $company_id,
                'closed_date' => $date
            ]);
            $employees->each(function ($employee) use ($company_id, $date) {
                $attendance = [
                    'company_id' => $company_id,
                    'employee_id' => $employee->id,
                    'attendance_date' => $date,
                    'status' => 'absent',
                    'check_in_time' => null,
                    'check_out_time' => null,
                    'remarks' => 'default from system close'
                ];
                $attendance = self::createAttendance($attendance);
                if (!$attendance) {
                    DB::rollBack();
                    throw new Exception('Unable to fill Attendance');
                }
            });
            DB::commit();
            return ['status' => 'success'];
        } catch (Exception $e) {
            DB::rollBack();
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }

    public static function uncloseAttendanceForTheDay(
        $date,
        $company_id
    ) {
        DB::beginTransaction();
        try {
            // checking if the date is already closed
            $attendance = ClosedDay::whereDate('closed_date', 'like', $date . "%")
                ->first();
            if (!$attendance) {
                DB::rollBack();
                return ['status' => 'fail', 'message' => 'The date is not closed yet'];
            }
            // delete all attendance records marked as absent from system close
            $attendances = Attendance::where('status', 'absent')
                ->where('remarks', 'default from system close')
                ->whereDate('attendance_date', 'like', $date . "%")
                ->get();
            foreach ($attendances as $att) {
                $att->delete();
            }
            // delete the closed day record
            $attendance->delete();
            DB::commit();
            return ['status' => 'success'];
        } catch (Exception $e) {
            DB::rollBack();
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }


}
