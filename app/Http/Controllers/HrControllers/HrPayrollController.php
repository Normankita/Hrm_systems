<?php

namespace App\Http\Controllers\HrControllers;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HrPayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::latest()->get();
        return view('hr.payroll.index', compact('payrolls'));
    }

    /**
     * Get all pending payrolls
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function pending()
    {
        $payrolls = Payroll::where('status', 'pending')->get();
        return view('hr.payroll.pending', compact('payrolls'));
    }

    public function approved()
    {
        $payrolls = Payroll::where('status', 'approved')->get();
        return view('hr.payroll.approved', compact('payrolls'));
    }

    public function rejected()
    {
        $payrolls = Payroll::where('status', 'rejected')->get();
        return view('hr.payroll.rejected', compact('payrolls'));
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
