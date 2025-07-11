<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AllowanceGroupAllowancePivot;
use App\Models\AllowanceGroupEmployeePivot;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ApiAllowanceGroupMembersController extends Controller
{
    // This controller is currently empty, but you can add methods to handle API requests related to allowance group members.
    // For example, you might want to add methods for adding or removing members from an allowance group.


    public function addMemberToGroupAllowance(Request $request, $groupId, $allowanceId)
    {
        $effectiveFrom = $request->effectiveFrom ?? now(); // Assuming effective_from is passed in the request
        $empCollection = collect($request->employees);
        $employees = $empCollection->pluck('id'); // Assuming employees are passed in the request
        $groupEmployees = AllowanceGroupEmployeePivot::where('allowance_group_id', $groupId)
            ->whereIn('employee_id', $employees)
            ->with(['employee'])
            ->get();

        $groupAllowance = AllowanceGroupAllowancePivot::where('allowance_group_id', $groupId)
            ->where('allowance_id', $allowanceId)
            ->first();
        DB::beginTransaction();
        if ($groupAllowance) {
            // try {
            $groupEmployees->each(function ($employeeGroup) use ($groupAllowance, $effectiveFrom, $empCollection) {
                $empExtraDetails = $empCollection->where('id', $employeeGroup->employee->id)->first();
                $employeeGroup->allowanceGroupAllowancesPivot()->attach(
                    $groupAllowance->id,
                    [
                        'amount' => $empExtraDetails['amount'], // Default amount, can be changed later
                        'effective_from' => $effectiveFrom,
                        'isActive' => true,
                        'allowance_frequency_id' => $empExtraDetails['frequency']['id'],
                    ]
                );
            });
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Member added to group allowance successfully',
                'data' => [
                    'group_id' => $groupId,
                    'allowance_id' => $allowanceId,
                    'members' => $groupAllowance->groupEmployeesPivot, // Assuming members are passed in the request
                ]
            ]);
            // } catch (Exception $throwable) {
            //     DB::rollBack();
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => $throwable->getMessage(),
            //     ], 500);
            // }
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Group allowance not found',
        ], 404);
    }
}
