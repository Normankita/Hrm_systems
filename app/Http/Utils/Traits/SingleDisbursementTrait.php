<?php

namespace App\Http\Utils\Traits;

use Carbon\Carbon;

class SingleDisbursementTrait
{

    public
     static function isEligible($employeeAllowance_details,
     $forDisburseDate)
    {

        $items = self::timeSpanInspector($employeeAllowance_details);
        if ($items->isEmpty()) {
            return true;
        }
        return self::canDisburse(
            $items,
            $forDisburseDate,
            $employeeAllowance_details->frequency
        );
    }


    private static function timeSpanInspector($item)
    {
        $effectiveFrom = Carbon::parse($item->effective_from ?? Carbon::now());
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
            return $disbursementCount >= $frequency->no_base_times ? false : true;
        }
        return true;
    }

}
