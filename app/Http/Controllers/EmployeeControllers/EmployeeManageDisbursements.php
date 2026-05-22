<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Enums\AllowanceGroups;
use App\Http\Controllers\Controller;
use App\Http\Resources\AllowanceGroupResource;
use App\Http\Resources\CategoryDisbursementResource;
use App\Http\Resources\GroupCategoryEmployeeAllowanceResource;
use App\Http\Resources\GroupedDisbursementResource;
use App\Http\Resources\IndividualDisbursementResource;
use App\Models\Allowance;
use App\Models\AllowanceGroup;
use App\Models\DisbursedAllowance;
use App\Models\Employee;
use App\Models\GroupCategoryEmployeeAllowance;
use Illuminate\Http\Request;

class EmployeeManageDisbursements extends Controller
{

    public function __construct(protected Employee $Employee, protected AllowanceGroup $group)
    {
    }


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
            return redirect()->back()->withErrors(
                ['basedOn' => 'Based on parameter is required.']
            );
        }
        if ($basedOn == AllowanceGroups::GROUP) {
            // All groups pointing to the company
            $groups = AllowanceGroup::where('isActive', true)
                ->with('allowance')
                ->get();
            $allowance = Allowance::all();
            $responseData = $groups->map(function ($group) {
                return AllowanceGroupResource::make($group)->resolve();
            });
            return view('employee.manage.disbursement_allowance.create.grouped')
                ->with('groups', $responseData)
                ->with('allowances', $allowance);
        } elseif ($basedOn == AllowanceGroups::INDIVIDUAL) {
            $allowances = Allowance::all();
            $employees = $this->Employee->getActiveEmployees();
            $groups = $this->group->activeGroups();
            return view('employee.manage.disbursement_allowance.create.individual')
                ->with('employees', $employees)
                ->with('allowances', $allowances)
                ->with('groups', $groups);
        } elseif ($basedOn == AllowanceGroups::CATEGORY) {
            $categories = Allowance::all();
            $groups = $this->group->activeGroups();
            return view('employee.manage.disbursement_allowance.create.category')
                ->with('categories', $categories)
                ->with('groups', $groups);
        } elseif ($basedOn == 'all') {
            return view('employee.manage.disbursement_allowance.create.all');
        }
        return redirect()->back()->with(
            'error',
            'Invalid based on parameter provided.'
        );
    }


    public function viewDisbursementsGroup(Request $request)
    {
        $ref = $request->query('ref');
        $basedOn = $request->query('basedOn');
        $disbursements = DisbursedAllowance::where(
            'entrence_reference',
            $ref
        )->get();
        switch ($basedOn) {
            case AllowanceGroups::INDIVIDUAL:
                $disbursements = IndividualDisbursementResource::collection(
                    $disbursements
                )->resolve();
                return view('employee.manage.disbursement_allowance.individual_view')
                    ->with('disbursements', $disbursements)
                    ->with('basedOn', $basedOn);

            case AllowanceGroups::CATEGORY:
                // fetching the groupcategoryemployeeallowancedetails first
                $objs = GroupCategoryEmployeeAllowance::whereIn(
                    'id',
                    $disbursements->pluck('disbursable_id')
                )->orderBy('allowance_group_allowance_pivot_id')
                ->get();
                $disbursements = GroupCategoryEmployeeAllowanceResource::collection(
                    $objs
                )->resolve();
                return view('employee.manage.disbursement_allowance.category_view')
                    ->with('disbursements', $disbursements)
                    ->with('basedOn', $basedOn);

            case AllowanceGroups::GROUP:
                $objs = GroupCategoryEmployeeAllowance::whereIn(
                    'id',
                    $disbursements->pluck('disbursable_id')
                )->orderBy('allowance_group_allowance_pivot_id')
                ->get();
                $disbursements = GroupCategoryEmployeeAllowanceResource::collection(
                    $objs
                )->resolve();
                return view('employee.manage.disbursement_allowance.group_view')
                    ->with('disbursements', $disbursements)
                    ->with('basedOn', $basedOn);
        }
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
