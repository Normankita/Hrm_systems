<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Services\PayrollService;
use App\Http\Services\PayslipPdfService;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class EmployeePayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payrolls = Payroll::with(['employee', 'pay_grade'])->latest()->get();
        return view('employee.manage.payroll.index', compact('payrolls'));

    }
    public function generateAll(Request $request)
    {
        $request = [];
        $payrolls = PayrollService::generatePayrollForAllEmployees();
        
        return redirect()->back()->with('success', count($payrolls) . ' payrolls generated.');
        
    }


    public function getEmployees()
    {
        $period = now()->format('Y-m');
        $employees = Employee::whereHas('pay_grades', function ($q) {
            $q->where('employee_pay_grade.status', true);
        })
            ->whereDoesntHave('payrolls', function ($q) use ($period) {
                $q->where('period', $period);
            })
            ->with([
                'pay_grades' => fn($q) => $q->where('employee_pay_grade.status', true),
                'deductions'
            ])
            ->get();
        return view('employee.manage.payroll.select-pay', compact('employees'));
    }




    public function generateForSelected(Request $request)
    {
        $employeeIds = $request->input('selected_employees');
        if (!$employeeIds) {
            return back()->with('error', 'No employees selected.');
        }
        $payrolls = PayrollService::generatePayrollForSelectedEmployees(
            false,
            $employeeIds
        );
        return redirect()->route('employee.manage.payrolls.index')
            ->with('success', count($payrolls) . ' payrolls generated.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function show(Employee $employee, Payroll $payroll)
    {
        // Make sure the payroll belongs to the employee
        if ($payroll->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access to payroll.');
        }

        $deductions = $payroll->deductions()->get(); // or however you're storing them

        return view('employee.manage.payroll.show', compact('employee', 'payroll', 'deductions'));
    }

    public function edit(Payroll $payroll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payroll $payroll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payroll $payroll)
    {
        //
    }
}
