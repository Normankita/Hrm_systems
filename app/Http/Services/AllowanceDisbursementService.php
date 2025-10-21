<?php

namespace App\Http\Services;

use App\Enums\AllowanceFrequncy;
use App\Enums\AllowanceGroups;
use App\Http\Utils\Traits\AllowanceGroupEmployeePivotTrait;
use App\Http\Utils\Traits\GroupCategoryDisbursementPageTrait;
use App\Models\Allowance;
use App\Models\AllowanceFrequency;
use App\Models\AllowanceGroup;
use App\Models\AllowanceGroupAllowancePivot;
use App\Models\AllowanceGroupEmployeePivot;
use App\Models\DisbursedAllowance;
use App\Models\Employee;
use App\Models\EmployeeAllowance;
use App\Models\GroupCategoryEmployeeAllowance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AllowanceDisbursementService
{
    use GroupCategoryDisbursementPageTrait, AllowanceGroupEmployeePivotTrait;
    /**
     * Handle the disbursement based on the type.
     *
     * @param Request $request
     * @return array
     */
    public function handleDisbursement($basedOn, $embeded): array
    {
        if ($basedOn === AllowanceGroups::CATEGORY) {
            $categoriesIds = $embeded;
            $categories = Allowance::whereIn('id', $categoriesIds)->get();
            return $this->categoryBasedDisbursement($categories);
        } elseif ($basedOn === AllowanceGroups::GROUP) {
            $groupsIds = $embeded;
            $groups = AllowanceGroup::whereIn('id', $groupsIds)->get();
            return $this->groupBasedDisbursement($groups);
        } elseif ($basedOn === AllowanceGroups::INDIVIDUAL) {
            $employeesIds = $embeded['employeesIds'];
            $employees = Employee::whereIn('id', $employeesIds)->get();
            return $this->individualBasedDisbursement($employees);
        }
        // Logic to handle disbursement based on the type
        // This is a placeholder for actual implementation
        return [
            'status' => 'success',
            'message' => 'Disbursement created successfully.'
        ];
    }

    public static function disburseWithGroupCategory($collection)
    {
        $gr_cat_emp_collection = self::formatForGroupCategoryDisburse(
            $collection
        );

        DB::beginTransaction();
        try {
            $disbursed = $gr_cat_emp_collection->map(
                function ($disburse) {
                    return DisbursedAllowance::create([
                        'type' => AllowanceGroups::CATEGORY,
                        'amount' => $disburse->amount,
                        'employee_id' => $disburse->employee->id,
                        'status' => true,
                        'company_id' => $disburse->employee->company_id,
                        'disbursable_type' => GroupCategoryEmployeeAllowance::class,
                        'disbursable_id' => $disburse->id,
                        'allowance_id' => $disburse->allowance->id
                    ]);
                }
            );

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Disbursement created successfully.',
                'data' => $disbursed,
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
        }
    }


    public static function disburseWithIndividualCategory(
        $allowanceEmployeePivotIds,
        $user
    ) {
        // select allowance employee pivot ids
        DB::beginTransaction();
        try {
            $allowances = DB::table('employee_allowance')
                ->whereIn('id', $allowanceEmployeePivotIds)
                ->get();
            DisbursedAllowance::insert(
                $allowances->map(function ($allowance) use ($user) {
                    return [
                        'type' => AllowanceGroups::INDIVIDUAL,
                        'amount' => $allowance->amount,
                        'company_id' => $user->company_id,
                        'employee_id' => $allowance->employee_id,
                        'status' => true,
                        'disbursable_id' => $allowance->id,
                        'disbursable_type' => EmployeeAllowance::class,
                        'allowance_id' => $allowance->allowance_id,
                        'created_at' => now(),
                    ];
                })->toArray()
            );
            DB::commit();
            return [
                'status' => 'success',
                'message' => 'Disbursement created successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => 'Failed to disburse allowances: ' . $e->getMessage(),
            ];
        }
    }

    private static function checkingGroupCategoryEligibilityDisburse($forDisburse)
    {
        $data = GroupCategoryEmployeeAllowance::where(
            'allowance_group_employee_pivot_id',
            $forDisburse->group_employee_pivot->id
        )
            ->where('allowance_group_allowance_pivot_id', $forDisburse->group_allowance_pivot->id)
            ->count();
        return [
            'status' => 'success',
            'message' => 'final variable attained',
            'data' => $data
        ];
    }

    private static function formatForGroupCategoryDisburse($collection)
    {
        // fetching its corresponding employees with intermediates datas
        return $collection->map(
            function ($gr_cat_emp) {
                return $gr_cat_emp->getRealDetailsDynamic();
            }
        );
    }


    private function categoryBasedDisbursement(Collection $categories): array
    {
        // Logic to handle category-based disbursement
        /**
         * Step 1
         * Fetching all groups that has this selected categories
         */
        $groups = AllowanceGroup::whereHas('allowances', function ($query) use ($categories) {
            $query->whereIn('id', $categories->pluck('id')->toArray());
        })->get();
        return [
            'status' => 'success',
            'message' => 'Category-based disbursement handled successfully.',
            'data' => $groups,
        ];
    }


    private function groupBasedDisbursement(Collection $groups): array
    {
        // Logic to handle group-based disbursement
        return [
            'status' => 'success',
            'message' => 'Group-based disbursement handled successfully.',
            'data' => $groups,
        ];
    }


    private function individualBasedDisbursement(Collection $employees): array
    {
        // Logic to handle individual-based disbursement
        return [
            'status' => 'success',
            'message' => 'Individual-based disbursement handled successfully.',
            'data' => $employees,
        ];
    }


    public static function groupAllowancePageDetails(
        AllowanceGroup $group,
        Allowance $allowance
    ) {
        $gr_allowance = $group->allowance()->where(
            'allowance_id',
            $allowance->id
        )->first();
        if (!$gr_allowance) {
            return [
                'status' => 'error',
                'message' => 'Allowance not found'
            ];
        }
        $gr_allowancePivot = AllowanceGroupAllowancePivot::where('allowance_group_id', $group->id)
            ->where('allowance_id', $allowance->id)
            ->with('activeGroupEmployeesPivot')
            ->first();

        $gr_employeePivot = $gr_allowancePivot->activeGroupEmployeesPivot;
        $groupCategoryEmpAllowance = $gr_employeePivot->pluck('pivot.id');

        $disbursed = DisbursedAllowance::where('type', AllowanceGroups::CATEGORY)
            ->where('disbursable_type', GroupCategoryEmployeeAllowance::class)
            ->whereIn('disbursable_id', $groupCategoryEmpAllowance)
            ->get();

        $response = AllowanceDisbursementService::groupDisburseDetails(
            $allowance,
            $disbursed
        );

        $empWithDisCounts = $response['empWithDisCounts'];
        $trueDisburseDetails = $response['disburseDetails'];
        $disbursementsGroupedByDay = $trueDisburseDetails->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        })->sortKeys();

        $groupWithEmp = AllowanceGroupEmployeePivot::withEmployees(
            $gr_employeePivot
        );
        $groupWithEmp = $groupWithEmp->map(function ($pivot) use ($empWithDisCounts) {
            $empId = $pivot->employee->id;
            if ($empWithDisCounts->has($empId)) {
                $pivot->count = $empWithDisCounts[$empId]['count'];
                $pivot->isEligible = $empWithDisCounts[$empId]['isEligible'];
            } else {
                $timing = $pivot->effective_from <= now();
                $pivot->count = $timing ? 0 : "N/A";
                $pivot->isEligible = $timing;
            }
            $freq = AllowanceFrequency::find($pivot->pivot->allowance_frequency_id);
            $pivot->frequency = $freq;
            return $pivot;
        });

        return [
            'status' => 'success',
            'details' => [
                'gr_employeePivot' => $gr_employeePivot,
                'gr_allowancePivot' => $gr_allowancePivot,
                'group' => $group,
                'allowance' => $allowance,
                'groupWithEmp' => $groupWithEmp,
                'disbursed' => $disbursementsGroupedByDay
            ]
        ];
    }


}
