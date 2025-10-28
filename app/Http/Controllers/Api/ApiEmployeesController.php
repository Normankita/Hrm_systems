<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;

class ApiEmployeesController extends Controller
{
    public function __construct(Employee $Employee)
    {
    }
    public function fetchEmployees()
    {
        // Fetch employees from the database
        $employees = $this->Employee->getActiveEmployees();

        // Format employees for select options
        $formattedEmployees = $employees->map(function ($employee) {
            return EmployeeResource::make($employee);
        });
        $response = [
            'status' => 'success',
            'employees' => $formattedEmployees,
        ];
        return response()->json($response, 200);
    }
}
