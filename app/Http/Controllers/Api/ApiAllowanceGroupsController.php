<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AllowanceGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiAllowanceGroupsController extends Controller
{

    public function removeMembersToGroup(Request $request, $groupId) {
        // Update isActive to false
        DB::table('allowance_group_employee')
            ->where('allowance_group_id', $groupId)
            ->whereIn('employee_id', $request->ids)
            ->update([
                'isActive' => false,
                'updated_at' => now(),
            ]);

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
