<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\AllowanceTrait;
use App\Models\AllowanceGroup;
use App\Models\AllowanceGroupAllowancePivot;
use App\Models\AllowanceGroupEmployeePivot;
use App\Models\GroupCategoryEmployeeAllowance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Catch_;
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

        DB::beginTransaction();
        try {
            $group = AllowanceGroup::find($groupId);

            // check if the allowance is already assigned to the group
            $allowanceExists = $group->allowance()
                ->where('allowance_id', $request->allowance_id)
                ->first();
            if (!$allowanceExists) {
                // Insert new allowance for the group
                $group->allowance()->attach($request->allowance_id);
                // Re-fetch the allowance including pivot data
                $allowanceExists = $group->allowance()
                    ->where('allowance_id', $request->allowance_id)
                    ->first();
            }

            foreach ($request->employees as $employee) {
                // checking if the employee is in the group
                $employeeGroup = AllowanceGroupEmployeePivot::where(
                    'allowance_group_id',
                    $group->id
                )
                    ->where('employee_id', $employee['id'])
                    ->first();
                if (!$employeeGroup) {
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'data not valid, please refresh the page and try again.'
                    ]);
                }
                // Check if the allowance is already assigned to the employee in the group
                $exists = GroupCategoryEmployeeAllowance::where(
                    'allowance_group_employee_pivot_id',
                    $employeeGroup->id
                )
                    ->where(
                        'allowance_group_allowance_pivot_id',
                        $allowanceExists->pivot->id
                    )
                    ->first();
                if ($exists) {
                    // Update the existing allowance if it exists
                    $exists->update([
                        'amount' => $employee['amount'],
                        'allowance_frequency_id' => $employee['frequency_id'],
                        'effective_from' => now(),
                        'isActive' => true,
                    ]);
                    continue;
                } else {
                    $groupCategoryEmployeeAllowance = GroupCategoryEmployeeAllowance::create(
                        [
                            'allowance_group_employee_pivot_id' => $employeeGroup->id,
                            'allowance_group_allowance_pivot_id' => $allowanceExists->pivot->id,
                            'amount' => $employee['amount'],
                            'allowance_frequency_id' => $employee['frequency_id'],
                            'effective_from' => now(),
                            'isActive' => true
                        ]
                    );
                    if (!$groupCategoryEmployeeAllowance) {
                        return response()->json([
                            'status' => 'fail',
                            'message' => 'Failed to assign allowance for an employee in the group'
                        ]);
                    }
                }
            }
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            return response()->json([
                'status' => 'fail',
                'message' => 'something went wrong - ' . $throwable->getMessage()
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => ' allowance assigned successfully'
        ]);
    }

}
