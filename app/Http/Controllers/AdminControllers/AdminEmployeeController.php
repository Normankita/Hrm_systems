<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use EmployeeTrait;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AdminEmployeeController extends Controller
{
    public function index()
    {
        $employees = EmployeeTrait::getAllEmployees();
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
        $rules = array(
            ['role_id' => 'requered'],
            ['department_id' => 'required'],
            ['company_id' => 'required'],
            ['first_name' => 'required'],
            ['last_name' => 'required'],
            ['email' => 'required', 'email'],
            ['phone' => 'required'],
            ['gender' => 'required'],
            ['date_of_birth' => 'required'],
            ['phone_number' => 'required'],
            ['national_id' => 'required'],
            ['marital_status' => 'required'],
            ['residential_address' => 'required'],
            ['tin_number' => 'required'],
            ['employee_type' => 'required'],
            ['date_of_hire' => 'required']
        );
        Validator::make($request->all(), $rules)
                    ->validate();
        $employee = EmployeeTrait::createEmployee($request->all());
        return redirect()->route('admin.employees.show', ['id' => $employee->id])
                        ->with('success', 'Employee created successfully');
    }

    public function show($id)
    {
        $employee = EmployeeTrait::getEmployeeById($id);
        return view('admin.employee.show')
            ->with('employee', $employee);
    }
}
