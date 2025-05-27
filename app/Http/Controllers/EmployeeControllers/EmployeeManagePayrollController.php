<?php
namespace App\Http\Controllers\EmployeeControllers;


use App\Http\Controllers\Controller;
use App\Http\Services\PayrollService;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class EmployeeManagePayrollController extends Controller
{
  
    public function index()
    {
        $payrolls = Payroll::with(['employee', 'pay_grade'])->latest()->get();
        return view('payroll.payroll.index', compact('payrolls'));

    }
    public function generateAll()
    {
        $payrolls = PayrollService::generatePayrollForAllEmployees();
        return redirect()->back()->with('success', count($payrolls) . ' payrolls generated.');
    }


    public function show(Employee $employee, Payroll $payroll)
    {
        // Make sure the payroll belongs to the employee
        if ($payroll->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access to payroll.');
        }

        $deductions = $payroll->deductions()->get(); // or however you're storing them

        return view('employee.manage.payroll.payments.show', compact('employee', 'payroll', 'deductions'));
    }

}
