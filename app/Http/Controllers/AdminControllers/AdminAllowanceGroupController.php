<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Http\Resources\UserResource;
use App\Http\Services\AllowanceDisbursementService;
use App\Http\Utils\Traits\AllowanceGroupTrait;
use App\Models\Allowance;
use App\Models\AllowanceFrequency;
use App\Models\AllowanceGroup;
use App\Models\AllowanceGroupEmployeePivot;
use Illuminate\Support\Facades\Validator;


class AdminAllowanceGroupController extends Controller
{
    use AllowanceGroupTrait;
    public function index()
    {
        $groups = AllowanceGroup::all();
        $allowances = Allowance::all();
        return view('admin.allowance_groups.index')
            ->with(['groups' => $groups, 'allowances' => $allowances]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:allowance_groups,name',
            'description' => 'nullable|string|max:255',
        ];
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate);
        }
        AllowanceGroup::create([
            'company_id' => auth()->user()->company_id,
            'created_by' => auth()->user()->id,
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return redirect()->back()->with('success', 'Allowance group created successfully');
    }


    public function edit($id)
    {
        $group = AllowanceGroup::where('id', $id)->first();

        // Employee That are eligible to be added in the group
        $employees = self::getEmployeeForAdditionSelection($group);
        if (!$group)
            return redirect()->back()->with('error', 'Allowance group not found');
        return view('admin.allowance_groups.edit')
            ->with('group', $group)
            ->with('employees', $employees);
    }


    public function getGroupMembers(AllowanceGroup $group)
    {
        $employees = self::getEmployeeForAdditionSelection($group);
        return view('admin.allowance_groups.members')
            ->with(['employees' => $employees, 'group' => $group]);
    }


    public function getGroupMembersToAssignAllowance(AllowanceGroup $group)
    {
        // fetching group categories assigned
        $categories = $group->allowance;
        $frequencies = AllowanceFrequency::all();
        // select allowance that is absent in the group
        $gr_allowance_pivots = $categories->pluck('pivot.allowance_id');
        // fetching the objects of this ids
        $allowances = Allowance::whereNotIn(
            'id',
            $gr_allowance_pivots
        )->get();
        $group = AllowanceGroup::find($group->id);
        $employees = $group->activeEmployees()->get();
        return view('admin.allowance_groups.assignAllowance')
            ->with([
                'employees' => $employees,
                'group' => $group,
                'frequencies' => $frequencies,
                'allowances' => $allowances,
                'categories' => $categories
            ]);
    }



    public function getGroupAllowanceDetails(
        AllowanceGroup $group,
        Allowance $allowance
    ) {
        $response = AllowanceDisbursementService::groupAllowancePageDetails(
            $group,
            $allowance
        );
        if ($response['status'] === 'error') {
            return redirect()->back()->with('error', $response['message']);
        }
        // select employees to add in the group
        return view('admin.allowance_groups.categoryDetails')
            ->with([
                'gr_employeePivot' => $response['details']['gr_employeePivot'],
                'gr_allowancePivot' => $response['details']['gr_allowancePivot'],
                'group' => $group,
                'allowance' => $allowance,
                'groupWithEmp' => $response['details']['groupWithEmp'],
                'disbursed' => $response['details']['disbursed']
            ]);
    }


    public function editMembers($group, $allowance)
    {
        $group = AllowanceGroup::find($group);
        $allowance = Allowance::find($allowance);
        if (!$group || !$allowance) {
            return redirect()->back()->with('error', 'Group or Category not found');
        }
        $groupMembersWithoutAllowance = AllowanceGroupEmployeePivot::getEligibleToBeAddedInAllowance(
            $allowance,
            $group
        )->map(function ($item) {
            return $item->getRealDetails($item->id)->employee;
        });
        return view('admin.allowance_groups.editMembers')
            ->with([
                'employees' => $groupMembersWithoutAllowance,
                'user' => new UserResource(auth()->user()),
                'group' => $group,
                'allowance' => $allowance
            ]);
    }

}
