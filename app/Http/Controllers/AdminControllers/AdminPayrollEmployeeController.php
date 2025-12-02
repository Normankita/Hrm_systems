<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\EmployeeService;
use App\Http\Utils\Traits\EmployeeTrait;
use App\Http\Services\PayrollService;
use App\Http\Services\PayslipPdfService;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminPayrollEmployeeController extends Controller
{
    use EmployeeTrait;
    public function __construct(private EmployeeService $employeeService)
    {
    }

    public function index(Request $request)
    {
        $backRoute = route('admin.payroll.employees.index');
        $payrolls = Payroll::latest()->get();
        return view(
            'admin.payroll.payments.index',
            compact('payrolls', 'backRoute')
        );
    }


    /**
     * This will return the page for pending payrolls ready to be
     * approved or rejected by someone that were created
     *
     * @return void
     */
    public function pending()
    {
        $payrolls = Payroll::where('status', 'pending')->get();
        return view('admin.payroll.payments.pending',
         compact('payrolls'));
    }


    /**
     * This function will return page for approved payrolls
     * @return \Illuminate\Contracts\View\View
     */
    public function approved()
    {
        $payrolls = Payroll::where('status', 'approved')->get();
        return view('admin.payroll.payments.approved',
         compact('payrolls'));
    }

    public function rejected()
    {
        $payrolls = Payroll::where('status', 'rejected')->get();
        return view(
            'admin.payroll.payments.rejected',
            compact('payrolls')
        );
    }


    /**
     * This will give the payrolls page that are rejected
     * @param Request $request
     * @param Payroll $payroll
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, Payroll $payroll)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);
        $data = [
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ];
        $payroll->update($data);
        $payroll->recordEvent('update', $data);
        return back()->with('success',
         'Payroll rejected successfully.');
    }


    /**
     * Display a listing of the resource.
     */
    public function manageIndex()
    {
        $payrolls = Payroll::select(
            DB::raw("entrence_reference"),
            DB::raw("MAX(created_at) as latest_creation"),
            DB::raw("COUNT(*) as payroll_count")
        )
            ->with(['employee', 'pay_grade'])
            ->groupBy('entrence_reference')
            ->get();
        return view(
            'employee.manage.payroll.index',
            compact('payrolls')
        );
    }


    public function singleGroupShow($entrence_reference)
    {
        $payrolls = Payroll::where('entrence_reference', $entrence_reference)
            ->with(['employee', 'pay_grade'])
            ->get();
        return view(
            'employee.manage.payroll.singleGroupShow',
            compact('payrolls', 'entrence_reference')
        );
    }


    public function generateAll(Request $request)
    {
        $request = [];
        $payrolls = PayrollService::generatePayrollForAllEmployees();

        return redirect()->back()->with('success',
         count($payrolls) . ' payrolls generated.');

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
        return view('employee.manage.payroll.select-pay',
         compact('employees'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function show(Payroll $payroll, Request $request)
    {
        $backRoute = $request->input('back', route('employee.manage.payrolls.index'));
        $employee = Employee::find($payroll->employee_id);

        $deductions = $payroll->deductions()->get();

        return view(
            'employee.manage.payroll.payments.show',
            compact(
                'employee',
                'payroll',
                'deductions'
            )
        )
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
