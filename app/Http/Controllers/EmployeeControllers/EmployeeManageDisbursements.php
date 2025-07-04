<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Enums\AllowanceGroups;
use App\Http\Controllers\Controller;
use App\Models\Allowance;
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
    public function create(Request $request)
    {
        $basedOn = $request->get('basedOn');
        if (!$basedOn) {
            return redirect()->back()->withErrors(['basedOn' => 'Based on parameter is required.']);
        }
        if ($basedOn == AllowanceGroups::GROUP) {
            return view('employee.manage.disbursement_allowance.create.grouped');
        } elseif ($basedOn == AllowanceGroups::INDIVIDUAL) {
            return view('employee.manage.disbursement_allowance.create.individual');
        } elseif ($basedOn == AllowanceGroups::CATEGORY) {
            $categories = Allowance::all();
            return view('employee.manage.disbursement_allowance.create.category')
                ->with('categories', $categories);
        } elseif ($basedOn == 'all') {
            return view('employee.manage.disbursement_allowance.create.all');
        }
        return redirect()->with(
            'error',
            'Invalid based on parameter provided.'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $basedOn = $request->post('basedOn');
        if ($basedOn == AllowanceGroups::INDIVIDUAL) {

        }elseif ($basedOn == AllowanceGroups::GROUP) {

        } elseif ($basedOn == AllowanceGroups::CATEGORY) {
            $categoriesIds = $request->post('categories', []);
            $categories = Allowance::whereIn('id', $categoriesIds)->get();
        } elseif ($basedOn == 'all') {
            // Handle all allowances disbursement logic here
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid based on parameter provided.',
            ], 400);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Disbursement created successfully.',
            'data' => $request->all(),
        ])
            ->setStatusCode(201);
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
