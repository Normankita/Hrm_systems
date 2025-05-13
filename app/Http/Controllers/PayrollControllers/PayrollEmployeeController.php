<?php

namespace App\Http\Controllers\PayrollControllers;

use App\Http\Controllers\Controller;
use App\Http\Services\EmployeeService;
use App\Http\Utils\Traits\EmployeeTrait;
use App\Models\PayGrade;
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
        dd($employee);
        // $pay_grades=PayGrade::all();
        // $attachments = $employee->attachments()->get();
        // $payrolls = $employee->payrolls()->get();

        return view('payroll.employee.show', compact('employee', 'attachments', 'payrolls', 'pay_grades'));
    }
    public function UpdatePayGrade(Request $request, PayGrade $payGrade){
        $employee = EmployeeTrait::getEmployeeById($request->id);
    }
}
