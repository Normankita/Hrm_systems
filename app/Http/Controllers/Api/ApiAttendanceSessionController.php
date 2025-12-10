<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\OldEmployeeShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiAttendanceSessionController extends Controller
{
    public function __construct()
    {
    }


    public function updateEmployeeSession(Request $request)
    {
        // register the changed shift to old shifts table
        DB::beginTransaction();
        try {
            // your logic to update employee session and register old shift
            $employeeId = $request->input('employee_id');
            $attendanceSessionId = $request->input('shift_id');
            // select the selected user
            $employee = Employee::find($employeeId);
            $companyId = $employee->company_id;
            // insert into old shifts table
            OldEmployeeShift::create([
                'company_id' => $companyId,
                'employee_id' => $employeeId,
                'attendance_session_id' => $employee->attendance_session_id,
                'changed_by' => $request->input('editor'),
            ]);
            // update employee attendance session
            $employee->attendance_session_id = $attendanceSessionId;
            $employee->save();
            // update employee session
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Employee session updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to update employee session',
                'error' => $e], 500);
        }
    }
}
