<?php
namespace App\Http\Utils\Traits;

use App\Models\AllowanceGroupEmployeePivot;
use App\Models\GroupCategoryEmployeeAllowance;
use Carbon\Carbon;

trait GroupCategoryDisbursementPageTrait
{
    private static function groupDisburseDetails(
        $allowance,
        $disbursed,
        $forDisburseDate = null
    ) {
        $forDisburseDate = $forDisburseDate ?? Carbon::now();
        $empWithDisCounts = collect();
        $trueDisburseDetails = collect();
        $disbursed->each(function ($item) use ($allowance, $forDisburseDate, $empWithDisCounts, $trueDisburseDetails) {
            $details = GroupCategoryEmployeeAllowance::getRealDetails(
                $item->disbursable_id
            );
            // if the allowance effective_from date is after tody skip it
            if (!($details->effective_from > $forDisburseDate)) {
                if ($details->allowance->id == $allowance->id) {
                    // checking if the employee is eligible to disburse
                    $isEligible = self::isEligible(
                        GroupCategoryEmployeeAllowance::class,
                        $details->id,
                        $forDisburseDate
                    );
                    $count = self::getCurrentCircleCount($details, $forDisburseDate);
                    $details->isEligible = $isEligible;
                    $details->count = $count;


                    $empId = ($details->employee->id);
                    $empWithDisCounts->put($empId, [
                        'employee' => $details->employee,
                        'count' => $count,
                        'isEligible' => $isEligible,
                        'effective_from' => $details->effective_from
                    ]);
                    $details->type = $item->type;
                    $details->disburseId = $item->id;
                    $trueDisburseDetails->push($details);
                }
            } else {
                $details->isEligible = false;
                $details->count = 'N/A';

                $empId = ($details->employee->id);
                $empWithDisCounts->put($empId, [
                    'employee' => $details->employee,
                    'count' => 'N/A',
                    'isEligible' => false,
                    'effective_from' => $details->effective_from,
                ]);
                $details->type = $item->type;
                $details->disburseId = $item->id;
                $trueDisburseDetails->push($details);
            }
        });
        return [
            'disburseDetails' => $trueDisburseDetails,
            'empWithDisCounts' => $empWithDisCounts,
            'forDisburseDate' => $forDisburseDate,
            'disbursed' => $disbursed
        ];
    }


    /**
     * Summary of isEligible
     * @param mixed $model // eg GroupCategoryEmployeeAllowance::class
     * @param mixed $gr_cat_empl_all_id // the id of the group category employee allowance
     * @param mixed $forDisburseDate // the date to check eligibility against
     * @return bool
     */
    private static function isEligible(
        $model,
        $gr_cat_empl_all_id,
        $forDisburseDate
    ) {
        $gr_cat_empl_all_id_details = $model::getRealDetails(
            $gr_cat_empl_all_id
        );
        $items = self::timeSpanInspector($gr_cat_empl_all_id_details);
        if ($items->isEmpty()) {
            return true;
        }
        return self::canDisburse(
            $items,
            $forDisburseDate,
            $gr_cat_empl_all_id_details->frequency
        );
    }


    private static function timeSpanInspector($item)
    {
        $effectiveFrom = Carbon::parse($item->effective_from);
        $daysApart = $item->frequency->days_apart;
        if ($item->object->allowanceDisbursements->isEmpty()) {
            // if there is no disbursement, we can return an empty collection
            return collect();
        }
        $disbursements = $item->object->allowanceDisbursements->groupBy(function ($disbursement) use ($effectiveFrom, $daysApart) {
            $disburseOn = Carbon::parse($disbursement->getDisbursementDay());
            // Calculate the difference in days between effectiveFrom and disburseOn
            $daysDiff = $effectiveFrom->diffInDays($disburseOn);
            // If the difference is negative, it means the disbursement date is before the effective date
            if ($daysDiff < 0) {
                // invali data that contain date older than what exoected
                return '1971-07-10 - 1971-08-08';
            }
            // Calculate which span the createdAt falls into
            $spanIndex = floor($daysDiff / $daysApart);
            // Optional: use actual span date as label
            $spanStart = $effectiveFrom->copy()->addDays($spanIndex * $daysApart);
            $spanEnd = $spanStart->copy()->addDays($daysApart - 1);

            return $spanStart->toDateString() . ' - ' . $spanEnd->toDateString();
        });
        return $disbursements;
    }


    /**
     * Receives the group category employee allowance item resource
     * and gives back the number of times remains to disburse in the current
     * disbursement cycle.
     * @param mixed $item
     * @return int
     */
    private static function getCurrentCircleCount(
        $item,
        $currentDisbursementDate
    ) {
        $elementsFromInspector = self::timeSpanInspector($item);

        if ($elementsFromInspector->isEmpty()) {
            return 0;
        }

        $lastItemKey = $elementsFromInspector->keys()->last();
        $lastItem = $elementsFromInspector->last();
        $lastItemStartDate = Carbon::parse(explode(' - ', $lastItemKey)[0]);
        $lastItemEndDate = Carbon::parse(explode(' - ', $lastItemKey)[1]);
        // checking if the current date is between the first and the last
        $isBetween = $currentDisbursementDate->between(
            $lastItemStartDate,
            $lastItemEndDate
        );
        if ($isBetween) {
            // count the numbers of disbursement and check if we can add more
            return $lastItem->count();
        }
        return 0;
    }


    private static function canDisburse(
        $elementsFromInspector,
        $currentDisbursementDate,
        $frequency
    ) {
        if ($elementsFromInspector->isEmpty()) {
            return true;
        }
        $lastItemKey = $elementsFromInspector->keys()->last();
        $lastItem = $elementsFromInspector->last();
        $lastItemStartDate = Carbon::parse(explode(' - ', $lastItemKey)[0]);
        $lastItemEndDate = Carbon::parse(explode(' - ', $lastItemKey)[1]);
        // checking if the current date is between the first and the last
        $isBetween = $currentDisbursementDate->between(
            $lastItemStartDate,
            $lastItemEndDate
        );
        if ($isBetween) {
            // count the numbers of disbursement and check if we can add more
            $disbursementCount = $lastItem->count();
            return $disbursementCount >= $frequency->no_times ? false : true;
        }
        return true;
    }
}
