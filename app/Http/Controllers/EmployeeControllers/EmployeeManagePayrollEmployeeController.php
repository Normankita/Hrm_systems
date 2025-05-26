<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Services\EmployeeService;
use App\Http\Utils\Traits\EmployeeTrait;
use App\Models\Employee;
use App\Models\PayGrade;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeManagePayrollEmployeeController extends Controller
{
    use EmployeeTrait;
    public function __construct(private EmployeeService $employeeService)
    {
    }

   public function index()
    {
        $payrolls = Payroll::latest()->get();
        return view('employee.manage.payroll.payments.index', compact('payrolls'));
    }

    public function pending()
    {
        $payrolls = Payroll::where('status', 'pending')->get();
        return view('employee.manage.payroll.payments.pending', compact('payrolls'));
    }

    public function approved()
    {
        $payrolls = Payroll::where('status', 'approved')->get();
        return view('employee.manage.payroll.payments.approved', compact('payrolls'));
    }

    public function rejected()
    {
        $payrolls = Payroll::where('status', 'rejected')->get();
        return view('employee.manage.payroll.payments.rejected', compact('payrolls'));
    }

    public function reject(Request $request, Payroll $payroll)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $payroll->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        return back()->with('message', 'Payroll rejected successfully.');
    }

    public function approveAll()
    {
        DB::transaction(function () {
            Payroll::where('status', 'pending')->update([
                'status' => 'approved'
            ]);
        });

        return back()->with('message', 'All pending payrolls approved (excluding rejections).');
    }
}
