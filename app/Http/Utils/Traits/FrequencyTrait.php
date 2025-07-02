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
                    'days_apart' => $this->getDaysSpan(30, $request->input('no_base_times')),
                ]);
                break;
            case 'year':
                $request->merge([
                    'days_apart' => $this->getDaysSpan(365, $request->input('no_base_times')),
                ]);
                break;
            case 'week':
                $request->merge([
                    'days_apart' => $this->getDaysSpan(
                        7,
                        $request->input('no_base_times')
                    ),
                ]);
                break;
            default:
                return [
                    'status' => 'fail',
                    'message' => 'Failed to create frequency, invalid data provided'
                ];
        }

        $allowanceFrequency = AllowanceFrequency::create(
            $request->all()
        );
        return $allowanceFrequency;
    }

    public function calculateDaysApart(
        $category_days,
        $category_count,
        $number_times,
    ): float|int {
        $days_apart = ($category_days * $category_count) / $number_times;
        return $days_apart;
    }

    public function getDaysSpan(
        $category_days,
        $category_count,
    ) {
        return $category_days * $category_count;
    }

}
