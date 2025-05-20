<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeManageLeaveTypeController extends Controller
{
    public function index() {
        $leaveTypes = LeaveType::all();
        return view('employee.leave_type.index')
            ->with('leaveTypes', $leaveTypes);
            
    }


    public function store(Request $request) {
        $rules = [
            'name' => 'required|string|max:255|unique:leave_types,name',
            'description' => 'nullable|string|max:255',
            'deducts_from_annual_leave' => 'boolean',
        ];
        $request->request->add(
            ['code' => str_replace(' ', '_', $request->name)]);
        Validator::make($request->all(), $rules)->validate();
        LeaveType::create($request->all());
        return redirect()->back()
        ->with('success', 'Leave Type created successfully');
    }

    public function update(Request $request, LeaveType $leaveType) {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'deducts_from_annual_leave' => 'boolean',
        ];
        Validator::make($request->all(), $rules)->validate();
        $leaveType->update([
            'name' => $request->name,
            'description' => $request->description,
            'deducts_from_annual_leave' => $request->deducts_from_annual_leave,
        ]);
        return redirect()->back()
        ->with('success',
         'Leave Type updated successfully');
    }


    public function destroy(LeaveType $leaveType) {
        $leaveType->delete();
        return redirect()->back()->with('success', 'Leave Type deleted successfully');
    }
}
