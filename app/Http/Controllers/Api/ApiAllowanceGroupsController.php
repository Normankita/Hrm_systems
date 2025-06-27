<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\AllowanceTrait;
use App\Models\AllowanceGroup;
use App\Models\AllowanceGroupEmployeePivot;
use App\Models\GroupCategoryEmployeeAllowance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ApiAllowanceGroupsController extends Controller
{
    use AllowanceTrait;

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
        if (!$group) {
            return response()->json([
                'status' => 'fail',
                'message' => 'This Operation was not successful'
            ]);
        }
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
                        'updated_at' => now(),
                    ]);
            } else {
                // Insert new record
                DB::table('allowance_group_employee')->insert([
                    'allowance_group_id' => $group->id,
                    'employee_id' => $employee['id'],
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

    public function assignAllowanceToGroup(Request $request, $groupId)
    {
        $user = $request->user;
        $group = AllowanceGroup::findOrFail($groupId);
        if (!$group) {
            return response()->json([
                'status' => 'fail',
                'message' => 'This Operation was not successful'
            ]);
        }
        foreach ($request->employees as $employee) {
            $employeeGroup =  DB::table('allowance_group_employee')
                ->where('allowance_group_id', $group->id)
                ->where('employee_id', $employee['id'])
                ->first();
            if (!$employeeGroup) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'data not found'
                ]);
            }
            $employee = $this->createAllowanceForEmployee($employee['id'], ['amount' => $employee['amount'], 'frequency_id' => $employee['frequency_id']], $request->allowance_id);
            if (!$employee) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'failed to create in employee'
                ]);
            }
            GroupCategoryEmployeeAllowance::create(
                [
                    'allowance_group_employee_id' => $employeeGroup->id,
                    'allowance_id' => $request->allowance_id
                ]
            );
        }
        return response()->json([
            'status' => 'success',
            'message' => ' allowance assigned successfully'
        ]);
    }

}
