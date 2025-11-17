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
     * @param $basedOn // (this can be group, category, individual)
     * @param $embeded // (this can be groups ids, category ids, employee ids)
     * @param array $allowanceIds
     * @param Request $request
     * @return array
     */
    public function handleDisbursement(
        $basedOn,
        $embeded,
        $allowanceIds = [],
        $date = null,
        array|null $employeeIds = null
    ): array {
        if ($basedOn === AllowanceGroups::CATEGORY) {
            $groupIds = $embeded;
            return $this->categoryBasedDisbursement(
                $allowanceIds,
                $groupIds
            );

        } elseif ($basedOn === AllowanceGroups::GROUP) {
            return $this->groupBasedDisbursement(
                $embeded,
                $allowanceIds,
                $date ?? Carbon::now(),
                $employeeIds
            );

        } elseif ($basedOn === AllowanceGroups::INDIVIDUAL) {
            $employeesIds = $embeded['employeesIds'];
            $employees = Employee::whereIn('id', $employeesIds)->get();
            return $this->individualBasedDisbursement($employees);

        } elseif ($basedOn == "individialGroup") {
            return $this->groupBasedDisbursement(
                $embeded,
                $allowanceIds,
                $date ?? Carbon::now(),
                $employeeIds
            );

        }
        // Logic to handle disbursement based on the type
        // This is a placeholder for actual implementation
        return [
            'status' => 'error',
            'message' => 'Something went wrong with the request.'
        ];
    }


    /**
     * This function handles category based disbursement to
     * all people in the selected groups
     */
    private function categoryBasedDisbursement(
        array $categoriesIds,
        array $groupsIds
    ): array {
        $employeeIds = Employee::getActiveEmployees()->pluck('id')->toArray();
        return $this->groupBasedDisbursement(
            $groupsIds,
            $categoriesIds,
            $date ?? Carbon::now(),
            $employeeIds
        );
    }


    /**
     * This function will disburse allowance to all groups under the selected
     * allowances.
     * @param array $groupsIds
     * @param array $allowanceIds
     * @param \Carbon\Carbon $date
     * @param array|null $employeeIds
     * @return array{data: \Illuminate\Database\Eloquent\Collection<int, AllowanceGroup>, message: string, status: string|array{message: string, status: string}}
     */
    private function groupBasedDisbursement(
        array $groupsIds,
        array $allowanceIds,
        Carbon $date,
        array|null $employeeIds = null
    ): array {

        $entrence_reference = uniqid('', true);
        // fetching all allowances under the group
        $groups = AllowanceGroup::with('allowance')
            ->with('employees', function ($query) {
                $query->where('isActive', true);
            })
            ->whereIn('allowance_groups.id', $groupsIds)
            ->get();
        DB::beginTransaction();
        try {
            // fetching the eligible user to receiver the allowance
            $groups->each(function ($group) use ($entrence_reference, $allowanceIds, $date, $employeeIds) {
                $employees = $group->employees;
                // if employees are provided filter them, to get only those with
                // the provided ids
                if ($employeeIds != null) {
                    $employeeIds = collect($employeeIds);
                    $employees = $employees->map(function ($employee) use ($employeeIds) {
                        if ($employeeIds->contains($employee->id)) {
                            return $employee;
                        }
                        return null;
                    })->filter()->values();
                }

                $employees->each(function ($employee) use ($group, $allowanceIds, $date, $entrence_reference) {
                    // checking the employee if he is part of the group
                    $groupEmployeePivot = AllowanceGroupEmployeePivot::where(
                        'employee_id',
                        $employee->id
                    )
                        ->where('allowance_group_id', $group->id)
                        ->first();
                    if (!$groupEmployeePivot) {
                        return;
                    }
                    // if found then we check for the allowances under the group
                    $groupAllowancePivot = AllowanceGroupAllowancePivot::where(
                        'allowance_group_id',
                        $group->id
                    )
                        ->whereIn('allowance_id', $allowanceIds)
                        ->get();
                    if (!$groupAllowancePivot) {
                        return;
                    }
                    $groupAllowancePivot->each(function ($allowancePivot) use ($groupEmployeePivot, $employee, $groupAllowancePivot, $date, $entrence_reference) {
                        // query the intermediate pivot between this two to get the
                        // employee allowance under this group, this will give use the information
                        // of how much to disburse and frequency
                        $groupCategoryEmployeeAllowance = GroupCategoryEmployeeAllowance::where(
                            'allowance_group_employee_pivot_id',
                            $groupEmployeePivot->id
                        )
                            ->where(
                                'allowance_group_allowance_pivot_id',
                                $allowancePivot->id
                            )
                            ->first();
                        if (!$groupCategoryEmployeeAllowance) {
                            return;
                        }
                        // check if the employee is eligible to disburse
                        $isEligible = self::isEligible(
                            GroupCategoryEmployeeAllowance::class,
                            $groupCategoryEmployeeAllowance->id,
                            $date
                        );
                        if ($isEligible) {
                            // disburse the allowance
                            DisbursedAllowance::create([
                                'type' => AllowanceGroups::GROUP,
                                'amount' => $groupCategoryEmployeeAllowance->amount,
                                'employee_id' => $employee->id,
                                'status' => true,
                                'company_id' => $employee->company_id,
                                'disbursable_type' => GroupCategoryEmployeeAllowance::class,
                                'disbursable_id' => $groupCategoryEmployeeAllowance->id,
                                'allowance_id' => $allowancePivot->allowance_id,
                                'entrence_reference' => $entrence_reference,
                            ]);
                        }
                    });
                });
            });
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
        }
        // disburse to the eligible employees
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
        $gr_allowancePivot = AllowanceGroupAllowancePivot::where(
            'allowance_group_id',
            $group->id
        )
            ->where('allowance_id', $allowance->id)
            ->with('activeGroupEmployeesPivot')
            ->first();

        $gr_employeePivot = $gr_allowancePivot->activeGroupEmployeesPivot;
        $groupCategoryEmpAllowance = $gr_employeePivot->pluck('pivot.id');

        $disbursed = DisbursedAllowance::whereIn(
            'type',
            [AllowanceGroups::CATEGORY, AllowanceGroups::GROUP]
        )
            ->where('disbursable_type', GroupCategoryEmployeeAllowance::class)
            ->whereIn('disbursable_id', $groupCategoryEmpAllowance)
            ->get();

        $response = AllowanceDisbursementService::groupDisburseDetails(
            $allowance,
            $disbursed
        );

        $empWithDisCounts = $response['empWithDisCounts'];
        $trueDisburseDetails = $response['disburseDetails'];
        $disbursementsGroupedByDay = $trueDisburseDetails
            ->sortByDesc('disburseId')
            ->groupBy(
                function ($item) {
                    return Carbon::parse($item->created_at)->format('Y-m-d');
                }
            );

        $groupWithEmp = AllowanceGroupEmployeePivot::withEmployees(
            $gr_employeePivot
        );
        $groupWithEmp = $groupWithEmp->map(function ($pivot) use (
            $empWithDisCounts) {
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


    public static function disburseWithGroupCategory($collection)
    {
        $entrence_reference = uniqid('', true);

        $gr_cat_emp_collection = self::formatForGroupCategoryDisburse(
            $collection
        );
        DB::beginTransaction();
        try {
            $disbursed = $gr_cat_emp_collection->map(
                function ($disburse) use ($entrence_reference) {
                    return DisbursedAllowance::create([
                        'type' => AllowanceGroups::CATEGORY,
                        'amount' => $disburse->amount,
                        'employee_id' => $disburse->employee->id,
                        'status' => true,
                        'company_id' => $disburse->employee->company_id,
                        'disbursable_type' => GroupCategoryEmployeeAllowance::class,
                        'disbursable_id' => $disburse->id,
                        'allowance_id' => $disburse->allowance->id,
                        'entrence_reference' => $entrence_reference,
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
        $entrence_reference = uniqid('', true);
        // select allowance employee pivot ids
        DB::beginTransaction();
        try {
            $allowances = DB::table('employee_allowance')
                ->whereIn('id', $allowanceEmployeePivotIds)
                ->get();
            DisbursedAllowance::insert(
                $allowances->map(function ($allowance) use ($user, $entrence_reference) {
                    return [
                        'type' => AllowanceGroups::INDIVIDUAL,
                        'amount' => $allowance->amount,
                        'company_id' => $user->company_id,
                        'employee_id' => $allowance->employee_id,
                        'status' => true,
                        'disbursable_id' => $allowance->id,
                        'disbursable_type' => EmployeeAllowance::class,
                        'allowance_id' => $allowance->allowance_id,
                        'entrence_reference' => $entrence_reference,
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

}
