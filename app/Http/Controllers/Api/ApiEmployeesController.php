<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use Illuminate\Http\Request;

class ApiEmployeesController extends Controller
{
    public function fetchEmployees() {
        // Fetch employees from the database
        $employees = \App\Models\Employee::all();

        // Format employees for select options
        $formattedEmployees = $employees->map(function ($employee) {
            return EmployeeResource::make($employee);
        });

        return response()->json($formattedEmployees);
    }
}
