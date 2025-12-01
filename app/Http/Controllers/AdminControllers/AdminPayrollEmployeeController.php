<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\EmployeeService;
use App\Http\Utils\Traits\EmployeeTrait;
use App\Models\Payroll;

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
        return view('admin.payroll.payments.index',
         compact('payrolls', 'backRoute'));
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
        return view('admin.payroll.payments.pending', compact('payrolls'));
    }


    /**
     * This function will return page for approved payrolls
     * @return \Illuminate\Contracts\View\View
     */
    public function approved()
    {
        $payrolls = Payroll::where('status', 'approved')->get();
        return view('admin.payroll.payments.approved', compact('payrolls'));
    }

    public function rejected()
    {
        $payrolls = Payroll::where('status', 'rejected')->get();
        return view('admin.payroll.payments.rejected',
        compact('payrolls'));
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
        return back()->with('success', 'Payroll rejected successfully.');
    }

}
