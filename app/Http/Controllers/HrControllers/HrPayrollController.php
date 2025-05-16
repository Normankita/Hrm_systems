<?php

namespace App\Http\Controllers\HrControllers;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Http\Request;

class HrPayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * This method is responsible for displaying a list of all payrolls in the
     * system. It is used by the view located at resources/views/hr/payroll/index.blade.php
     */
    public function index()
    {
        // Get all payrolls from the database
        $payrolls = Payroll::all();

        // Return the view with the payrolls
        return view('hr.payroll.index', compact('payrolls'));
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
