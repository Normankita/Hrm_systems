<?php
namespace App\Http\Controllers\EmployeeControllers;


use App\Http\Controllers\Controller;
use App\Http\Services\PayrollService;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class EmployeeManagePayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

    return view('hr.payroll.show', compact('employee', 'payroll', 'deductions'));
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
