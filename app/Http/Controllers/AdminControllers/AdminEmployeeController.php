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
        dd($employees);
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
        return view('admin.employee.show', $employee->id)
            ->with('employee', $employee);
    }
}
