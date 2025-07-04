<?php

namespace App\Http\Services;

use App\Enums\AllowanceGroups;
use App\Models\Allowance;
use App\Models\AllowanceGroup;
use App\Models\DisbursedAllowance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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
