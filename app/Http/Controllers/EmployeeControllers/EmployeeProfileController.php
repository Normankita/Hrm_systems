<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\EmployeeTrait;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;

class EmployeeProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employee = Auth::user()->employee;
        dd($employee);
        // return view('employee.profile.index', compact('employee'));
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
    dd($employee);
        // Split full name
        $fullName = $employee->full_name;
        $nameParts = explode(' ', $fullName, 2); // Only split into 2 parts: first and last
        $employee->first_name = $nameParts[0];
        $employee->last_name = $nameParts[1] ?? '';
    
    
        return view('employee.profile.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $rules = [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'phone' => '',
            'gender' => '',
            'date_of_birth' => '',
            'phone_number' => '',
            'national_id' => '',
            'marital_status' => '',
            'residential_address' => '',
            'tin_number' => ''
        ];
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }
        // adding company_id and department_id to the request
        $request->merge([
            'full_name' => $request->input('first_name') . ' ' . $request->input('last_name'), 
        ]);
        $employee = EmployeeTrait::getEmployeeById($employee->id);
        $employee->update($request->all());

        
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
