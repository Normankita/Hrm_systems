<?php

namespace App\Http\Services;

use App\Enums\AllowanceGroups;
use App\Models\Allowance;
use App\Models\AllowanceGroup;
use App\Models\DisbursedAllowance;
use App\Models\Employee;
use App\Models\GroupCategoryEmployeeAllowance;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AllowanceDisbursementService
{
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
        $gr_cat_emp_collection = self::formatForGroupCategoryDisburse($collection);
        // Give employee the desired allowance
        DB::beginTransaction();
        try {
            return $gr_cat_emp_collection->map(function ($disburse) {
                // checking the employees that are aligible to receijve the allowance
                $response = self::checkingGroupCategoryEligibilityDisburse(
                    $disburse);
                return $response;
                // DisbursedAllowance::create([
                //     'type' => AllowanceGroups::INDIVIDUAL,
                //     'amount' => $disburse->amount,
                //     'employee_id' => $disburse->employee->id,
                //     'status' => true,
                //     'company_id' => $disburse->employee->company_id,
                //     'disbursable_type' => GroupCategoryEmployeeAllowance::class,
                //     'disbursable_id' => $disburse->id
                // ]);
            });
            DB::commit();
            return [
                'status' => 'success',
                'message' => 'Disbursement created successfully.',
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => $th
            ];
        }

    }

    private static function checkingGroupCategoryEligibilityDisburse($forDisburse   )
    {
        $data = GroupCategoryEmployeeAllowance::where(
            'allowance_group_employee_pivot_id', $forDisburse->group_employee_pivot->id)
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
}
