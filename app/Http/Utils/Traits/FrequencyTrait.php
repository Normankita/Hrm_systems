<?php

namespace App\Http\Utils\Traits;

use App\Models\AllowanceFrequency;

trait FrequencyTrait
{

    /**
     * service to create allowance frequency
     * @param mixed $request
     * @return AllowanceFrequency|array{message: string, status: string}
     */
    public function createFrequency($request)
    {
        $daysApart = $this->resolveDaysApart($request);
        if ($daysApart === false) {
            return [
                'status' => 'fail',
                'message' => 'Failed to create frequency, invalid data provided'
            ];
        }
        $request->merge(['days_apart' => $daysApart]);
        return AllowanceFrequency::create($request->all());
    }



    /**
     * Service to update allowance frequency
     * @param mixed $request
     * @param mixed $id
     * @return AllowanceFrequency|array{message: string, status: string|\Illuminate\Database\Eloquent\Collection<int, AllowanceFrequency>}
     */
    public function updateFrequencyService($request, $id)
    {
        $frequency = AllowanceFrequency::find($id);
        if (!$frequency) {
            return [
                'status' => 'fail',
                'message' => 'Frequency not found'
            ];
        }
        $daysApart = $this->resolveDaysApart($request);
        if ($daysApart === false) {
            return [
                'status' => 'fail',
                'message' => 'Failed to update frequency, invalid data provided'
            ];
        }
        $request->merge(['days_apart' => $daysApart]);
        $frequency->update($request->all());
        return $frequency;
    }


    /**
     * Centralized base-category → days logic
     */
    protected function resolveDaysApart($request)
    {
        $baseCategory = $request->input('base_category');
        $count = $request->input('no_base_times');
        return match ($baseCategory) {
            'week'  => $this->getDaysSpan(7, $count),
            'month' => $this->getDaysSpan(30, $count),
            'year'  => $this->getDaysSpan(365, $count),
            default => false,
        };
    }


    public function calculateDaysApart(
        $category_days,
        $category_count,
        $number_times
    ): float|int {
        return ($category_days * $category_count) / $number_times;
    }

    public function getDaysSpan(
        $category_days,
        $category_count
    ) {
        return $category_days * $category_count;
    }
}
