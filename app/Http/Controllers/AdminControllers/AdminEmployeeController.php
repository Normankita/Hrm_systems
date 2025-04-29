<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\EmployeeTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AdminEmployeeController extends Controller
{
    public function index()
    {
        $employees = Auth::user()->company->employees()
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($employees);
        return view('admin.employee.index')
            ->with('employees', $employees);
    }
    public function create()
    {
        $roles = Role::where('name', '!=', 'ADMIN')->get();
        return view('admin.employee.create')
            ->with('roles', $roles);
    }

    public function store(Request $request) {
        $rules = [
            'role_id' => '',
            'department_id' => '',
            'company_id' => '',
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
            'tin_number' => '',
            'employee_type' => '',
            'date_of_hire' => ''
        ];
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }
        // adding company_id and department_id to the request
        $request->merge([
            'company_id' => Auth::user()->company_id,
            'department_id' => $request->input('department_id'),
            'full_name' => $request->input('first_name') . ' ' . $request->input('last_name'),
            
        ]);
        $employee = EmployeeTrait::createEmployee($request->all());
        return redirect()->route('admin.employees.show', $employee->id)
                        ->with('success', 'Employee created successfully');
    }

    public function show($id)
    {
        $employee = EmployeeTrait::getEmployeeById($id);
        return view('admin.employee.show', ['employee' => $employee]);
    }
    

    public function edit($id)
    {
        $employee = EmployeeTrait::getEmployeeById($id);
    
        // Split full name
        $fullName = $employee->full_name;
        $nameParts = explode(' ', $fullName, 2); // Only split into 2 parts: first and last
        $employee->first_name = $nameParts[0];
        $employee->last_name = $nameParts[1] ?? '';
    
        $roles = Role::where('name', '!=', 'ADMIN')->get();
    
        return view('admin.employee.edit', compact('employee', 'roles'));
    }
    
    public function update(Request $request, $id)
    {
        $rules = [
            'role_id' => '',
            'department_id' => '',
            'company_id' => '',
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
            'tin_number' => '',
            'employee_type' => '',
            'date_of_hire' => ''
        ];
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }
        // adding company_id and department_id to the request
        $request->merge([
            'company_id' => Auth::user()->company_id,
            'department_id' => $request->input('department_id'),
            'full_name' => $request->input('first_name') . ' ' . $request->input('last_name'),
            
        ]);
        $employee = EmployeeTrait::getEmployeeById($id);
        $employee->update($request->all());

        
        return redirect()->route('admin.employees.show', $employee->id)
                        ->with('success', 'Employee updated successfully');
    }
}
 