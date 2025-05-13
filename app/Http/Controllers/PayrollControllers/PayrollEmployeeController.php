<?php

namespace App\Http\Controllers\PayrollControllers;

use App\Http\Controllers\Controller;
use App\Http\Services\EmployeeService;
use App\Http\Utils\Traits\EmployeeTrait;
use App\Models\Employee;
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
        $employee = Employee::find($id);
        $pay_grades=PayGrade::all();
        $attachments = $employee->attachments()->get();
        $payrolls = $employee->payrolls()->get();

        return view('payroll.employee.show', compact('employee', 'attachments', 'payrolls', 'pay_grades'));
    }
public function UpdatePayGrade(Request $request, Employee $employee)
{
    // Validate the request input
    $request->validate([
        'pay_grade_id' => 'required|exists:pay_grades,id',
    ]);

    // Update the pay grade
    $employee->update([
        'pay_grade_id' => $request->pay_grade_id
    ]);

    return back()->with('success', 'Pay grade updated successfully.');
}

}
