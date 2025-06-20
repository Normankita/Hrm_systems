<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\HasEvents;
use App\Models\AllowanceGroup;
use App\Models\AllowanceGroupEmployeePivot;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ApiAllowanceGroupsController extends Controller
{

    public function removeMembersFromGroup(Request $request, $groupId)
    {
        $employeesIds = $request->employees;
        $user = $request->user;
        if (count($employeesIds) < 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'No employees selected',
            ]);
        }

        DB::beginTransaction();
        $effectedRow = AllowanceGroupEmployeePivot::where('allowance_group_id', $groupId)
            ->whereIn('employee_id', $employeesIds)
            ->get();
        try {
            foreach ($effectedRow as $dataRow) {
                $dataRow->recordEvent(
                    'update',
                    $dataRow->toArray(),
                    $user['id']
                );
            }
            DB::table('allowance_group_employee')
                ->where('allowance_group_id', $groupId)
                ->whereIn('employee_id', $employeesIds)
                ->update([
                    'isActive' => false,
                    'updated_at' => now(),
                ]);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $throwable->getMessage(),
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Employee removed from group successfully',
        ]);
    }

    public function addMembersToGroup(Request $request, $groupId)
    {
        // attach employees to the designated grouph
        $user = $request->user;
        $group = AllowanceGroup::findOrFail($groupId);

        foreach ($request->employees as $employee) {
            $exists = DB::table('allowance_group_employee')
                ->where('allowance_group_id', $group->id)
                ->where('employee_id', $employee['id'])
                ->first();

            if ($exists) {
                // Update isActive to true
                DB::table('allowance_group_employee')
                    ->where('allowance_group_id', $group->id)
                    ->where('employee_id', $employee['id'])
                    ->update([
                        'isActive' => true,
                        'amount' => $employee['amount'],
                        'updated_at' => now(),
                    ]);
            } else {
                // Insert new record
                DB::table('allowance_group_employee')->insert([
                    'allowance_group_id' => $group->id,
                    'employee_id' => $employee['id'],
                    'amount' => $employee['amount'],
                    'isActive' => true,
                    'created_at' => now(),
                ]);
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Employees added to group successfully'
        ]);
    }

}
