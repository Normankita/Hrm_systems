<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Models\DisbursedAllowance;
use Illuminate\Http\Request;

class EmployeeManageDisbursements extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $category = $request->get('category') ?? null;
        $disbursements = DisbursedAllowance::all();
        return view('employee.manage.disbursement_allowance.index')
            ->with(['disbursements' => $disbursements])
            ->with('category', $category);
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
    public function show(DisbursedAllowance $disbursedAllowance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DisbursedAllowance $disbursedAllowance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DisbursedAllowance $disbursedAllowance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DisbursedAllowance $disbursedAllowance)
    {
        //
    }
}
