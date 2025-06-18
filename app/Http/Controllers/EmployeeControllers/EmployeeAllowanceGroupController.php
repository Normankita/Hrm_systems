<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\AllowanceGroupTrait;
use App\Models\AllowanceGroup;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeAllowanceGroupController extends Controller
{
    use AllowanceGroupTrait;
    public function index() {
        $groups = AllowanceGroup::all();
        return view('employee.manage.allowance_groups.index')
            ->with('groups', $groups);
    }

    public function store(Request $request) {
        $rules = [
            'name' => 'required|string|max:255',
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


    public function edit($id) {
        $group = AllowanceGroup::findOrFail($id);
        // Employee That are eligible to be added in the group
        $employees = self::getEmployeeForAdditionSelection($group);
        if(!$group) return redirect()->back()->with('error', 'Allowance group not found');
        return view('employee.manage.allowance_groups.edit')
            ->with('group', $group)
            ->with('employees', $employees);
    }
}
