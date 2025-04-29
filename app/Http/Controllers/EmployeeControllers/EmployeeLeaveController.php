<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class EmployeeLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaves = Leave::with([
            'employee',
            'leaveType'
        ])->where('employee_id', auth()->user()->employee->id)
         ->get();

        return view('employee.leave.index', compact('leaves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $leaveTypes = LeaveType::all();
        $employees = Employee::all();

        return view('employee.leave.request', compact('leaveTypes', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:255',
        ]);
        $request->merge(['employee_id' => auth()->user()->employee->id]);
        $request->merge(['status' => 'pending']); // Assuming 1 is the ID for 'Pending' status

        Leave::create($request->all());

        return redirect()->route('employees.leave.status')->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {
        return view('employee.leave.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leave $leave)
    {
        // Retrieve all leave types to populate the dropdown
        $leaveTypes = LeaveType::all();
    
        // You can pass only the `leaveTypes` variable if the employees data isn't needed in the blade
        // Since the blade doesn’t seem to require employees information in the form, we can omit that.
        return view('employee.leave.edit', compact('leave', 'leaveTypes'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Leave $leave)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:255',
        ]);

        $request->merge(['employee_id' => auth()->user()->employee->id]);
        $request->merge(['status' => 'pending']); // Assuming 1 is the ID for 'Pending' status

        $leave->update($request->all());

        return redirect()->route('employees.leave.status')->with('success', 'Leave request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        $leave->delete();

        return redirect()->route('employees.leave.status')->with('success', 'Leave request canceled successfully.');
    }
}
