<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Department;
use App\Models\DesignationRoleMapping;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::with([
            'company',
            'department',
            'designation.designation' // Accessing nested DesignationRoleMapping -> Designation
        ])->get();
    
        return view('employee.index', compact('employees'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $designations = DesignationRoleMapping::with('designation')
                    ->whereHas('designation', function($query) {
                        $query->where('is_active', true);
                    })
                    ->get()
                    ->pluck('designation')
                    ->unique('id');

    $departments = Department::all();
    $companies = Company::all();

    return view('employee.create', compact('designations', 'departments', 'companies'));
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
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
