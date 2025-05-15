<?php

namespace App\Http\Controllers\PayrollControllers;

use App\Http\Controllers\Controller;
use App\Http\Services\PayrollService;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Payroll $payroll)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
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
