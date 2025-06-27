<?php
namespace App\Http\Utils\Traits;

use App\Models\AllowanceFrequency;


trait FrequencyTrait
{
    public function createFrequency($request)
    {
        switch ($request->input('base_category')) {
            case 'month':
                $request->merge([
                    'days_apart' => $this->calculateDaysApart(30, $request->input('no_base_times'), $request->input('no_times')),
                ]);
                break;
            case 'year':
                $request->merge([
                    'days_apart' => $this->calculateDaysApart(365, $request->input('no_base_times'), $request->input('no_times')),
                ]);
                break;
            case 'week':
                $request->merge([
                    'days_apart' => $this->calculateDaysApart(7, $request->input('no_base_times'), $request->input('no_times')),
                ]);
                break;
            default:
                return [
                    'status' => 'fail',
                    'message' => 'Failed to create frequency, invalid data provided'
                ];
        }

        $allowanceFrequency = AllowanceFrequency::create($request->all());
        return $allowanceFrequency;
    }

    private function calculateDaysApart($category_days, $category_count, $number_times): float|int
    {
        $days_apart = ($category_days * $category_count) / $number_times;
        return $days_apart;
    }

}