<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Services\PayrollService;
use App\Http\Services\PayslipPdfService;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeePayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payrolls = Payroll::select(
            DB::raw("entrence_reference"),
            DB::raw("MAX(created_at) as latest_creation"),
            DB::raw("COUNT(*) as payroll_count")
        )
            ->with(['employee', 'pay_grade'])
            ->groupBy('entrence_reference')
            ->orderBy('latest_creation', 'desc')
            ->get();
        return view(
            'employee.manage.payroll.index',
            compact('payrolls')
        );
    }

    public function singleGroupShow($entrence_reference)
    {
        $backRoute = route('employee.manage.payrolls.index');
        $payrolls = Payroll::where('entrence_reference', $entrence_reference)
            ->with(['employee', 'pay_grade'])
            ->get();
        return view('employee.manage.payroll.singleGroupShow',
         compact('payrolls', 'entrence_reference'));
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


    /**
     * Store a newly created resource in storage.
     */
    public function show(Payroll $payroll, Request $request)
    {
        $backRoute = $request->input('back', route('employee.manage.payrolls.index')    );
        $employee = Employee::find($payroll->employee_id);

        $deductions = $payroll->deductions()->get();

        return view('employee.manage.payroll.payments.show',
         compact('employee',
         'payroll', 'deductions'))
         ->with('backRoute', $backRoute);
    }



    public function downloadPayslip($id)
    {
        $payroll = Payroll::findOrFail($id);

        if (!$payroll->payslip_path || !Storage::exists($payroll->payslip_path)) {
            abort(404, 'Payslip not found.');
        }

        return Storage::download($payroll->payslip_path, 'Payslip_' . $payroll->id . '.pdf');
    }


}
