<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\DailyAttendanceService;
use App\Http\Utils\Traits\AttendanceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $employeeIds = $request->employees_ids;
        $checkIn = $request->check_in;
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
                $reqDetails['check_in'] = $checkIn ??
                    ($request->state == 'present' ? now()->format('H:i:s') : null);
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
                        'error' => 'Failed to create attendance record',
                    ], 500);
                }
            }
            DB::commit();
            return response()->json([
                'success' => 'Attendance record created successfully',
                'message' => 'Attendance records created successfully for selected employees',
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
}


