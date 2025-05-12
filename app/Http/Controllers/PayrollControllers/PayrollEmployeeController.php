<?php

namespace App\Http\Controllers\PayrollControllers;

use App\Http\Controllers\Controller;
use App\Http\Services\EmployeeService;
use App\Http\Utils\Traits\EmployeeTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayrollEmployeeController extends Controller
{
        public function __construct(private EmployeeService $employeeService)
    {
    }

        public function index()
    {
        $employees = Auth::user()->company->employees()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('payroll.employee.index', compact('employees'));
    }

        public function show($id)
    {
        $employee = EmployeeTrait::getEmployeeById($id);
        $attachments = $employee->attachments()->get();
        $payrolls = $employee->payrolls()->get();

        return view('payroll.employee.show', compact('employee', 'attachments', 'payrolls'));
    }
}
