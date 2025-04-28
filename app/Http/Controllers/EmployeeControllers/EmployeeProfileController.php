<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\EmployeeTrait;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employee = Auth::user()->employee;
        return view('employee.profile.index', compact('employee'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $employee = EmployeeTrait::getEmployeeById($id);
        // Split full name
        $fullName = $employee->full_name;
        $nameParts = explode(' ', $fullName, 2);
        // Only split into 2 parts: first and last
        $employee->first_name = $nameParts[0];
        $employee->last_name = $nameParts[1] ?? '';
        return view('employee.profile.edit',
        compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
{
    $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'gender' => 'nullable|string|max:20',
        'date_of_birth' => 'nullable|date',
        'phone_number' => 'nullable|string|max:20',
        'national_id' => 'nullable|string|max:50',
        'marital_status' => 'nullable|string|max:50',
        'residential_address' => 'nullable|string|max:255',
        'tin_number' => 'nullable|string|max:50',
    ];

    $validate = Validator::make($request->all(), $rules);

    if ($validate->fails()) {
        return redirect()->back()
            ->withErrors($validate)
            ->withInput();
    }

    // Prepare the data correctly
    $data = $request->all();
    $data['full_name'] = $request->input('first_name') . ' ' . $request->input('last_name');

    // Use the trait here
    EmployeeTrait::updateEmployee($employee->id, $data);

    return redirect()->route('employees.profile.index')
        ->with('success', 'Employee updated successfully');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
