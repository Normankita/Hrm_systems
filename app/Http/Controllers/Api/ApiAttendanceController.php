<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\DailyAttendanceService;
use App\Http\Utils\Traits\AttendanceTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiAttendanceController extends Controller
{
    use AttendanceTrait;

    /**
     * Summary of manualEntryStore
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function manualEntryStore(Request $request)
    {
        $rules = [
            'employees_ids' => 'required|array',
            'type' => 'sometimes|in:check_in,check_out',
            'state' => 'required|in:present,absent,late,leave',
        ];
        // validate icoming data first
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return response()->json([
                'error' => 'Fail to create attendance record',
                'message' => $validate->errors()->first(),
            ], 422);
        }
        $types = [
            'check_in' => 'check_in_time',
            'check_out' => 'check_out_time',
        ];
        $employeeIds = $request->employees_ids;
        $type = $request->type;
        $time = $request->time;

        $trueType = $types[$type];

        if (empty($employeeIds)) {
            return response()->json([
                'error' => 'Fail to create attendance record, no employee provided',
            ], 422);
        }
        DB::beginTransaction();
        try {
            foreach ($request->employees_ids as $employeeId) {
                $reqDetails = [];
                $reqDetails['employee_id'] = $employeeId;
                $reqDetails['type'] = $type;
                $reqDetails[$trueType] = $time ??
                    ($request->state == 'present' ? null : null);
                $reqDetails['date'] = $request->date ?? now()->format('Y-m-d');
                $reqDetails['status'] = $request->state ?? 'present';
                $reqDetails['remarks'] = $request->remarks ?? '';

                $reqDetails = (object) $reqDetails;
                $validate = AttendanceTrait::manualEntryValidation($reqDetails);
                if ($validate->fails()) {
                    DB::rollBack();
                    return response()->json([
                        'error' => 'Fail to create attendance record',
                        'message' => $validate->errors()->first(),
                    ], 422);
                }
                // convert $reqDetails to object
                $attendance = self::manualEntryStoreTrait($reqDetails);
                if (!$attendance) {
                    DB::rollBack();
                    return response()->json([
                        'error' => 'Failed to create attendance record, make sure you chose the correct datasets',
                    ], 500);
                }
            }
            DB::commit();
            return response()->json([
                'success' => 'Attendance record created successfully',
                'message' => 'Operation completed successfully',
                'attendance' => $attendance,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to create attendance record',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function closeAttendance(Request $request)
    {
        $rules = [
            'id' => 'required',
            'date' => 'required'
        ];
        $validate = Validator::make($request->all(), $rules);
        if($validate->fails()) {
            return response()->json([
                'error' => 'bad parameter given'  
            ],  401);
        }
        $user = User::find($request->input('id'));
        $date = $request->input('date');
        $response = AttendanceTrait::closeAttendanceForTheDay($date,
            $user->company->id);
        if ($response['status'] == 'fail') {
            return response()->json([
                'error' => $response['message'],
            ], 500);
        }
        return response()->json([
            'message' => 'Attendance closed for the day successfully',
        ], 200);
    }
}


