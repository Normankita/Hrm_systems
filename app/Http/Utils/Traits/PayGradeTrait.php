<?php

namespace App\Http\Utils\Traits;



use App\Models\PayGrade;
use Illuminate\Http\Request;

trait PayGradeTrait
{
    protected function validatePayGrade(Request $request, $payGradeId = null)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:pay_grades,name' . ($payGradeId ? ',' . $payGradeId : ''),
            'description' => 'nullable|string|max:255',
        ];
        return $request->validate($rules);
    }

    protected function createPayGrade(Request $request)
    {
        return PayGrade::create($request->all());
    }

    protected function updatePayGrade(Request $request, PayGrade $payGrade)
    {
        $payGrade->update($request->all());
        return $payGrade;
    }
}
