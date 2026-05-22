<?php

namespace App\Http\Utils\Traits;



use App\Models\PayGrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait PayGradeTrait
{
    /**
     * Summary of validatePayGrade
     * @param \Illuminate\Http\Request $request
     * @param mixed $payGradeId
     * @return array{message: string, status: string}
     */
    protected function validatePayGrade(Request $request, $payGradeId = null)
    {
        if ($payGradeId) {
            $rules = [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('pay_grades', 'name')
                        ->ignore($payGradeId),
                ],
                'description' => 'nullable|string|max:255',
            ];
        } else {
            $rules = [
                'name' => 'required|string|max:255|unique:pay_grades,name',
                'description' => 'nullable|string|max:255',
            ];
        }
        $data = $request->all();
        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return [
                'status' => 'error',
                'message' => $validate->errors()->first(),
            ];
        }
        // ensure that the new paygrade ranges in not in any of the grade
        // Custom validation for salary range intersection
        $payGradeId = $payGradeId ?? 0;
        $overlaps = PayGrade::whereNot('id', $payGradeId)
            ->where(function ($query) use ($data) {
                $query->whereBetween('base_salary', [$data['base_salary'], $data['max_salary']])
                    ->orWhereBetween('max_salary', [$data['base_salary'], $data['max_salary']])
                    ->orWhere(function ($q) use ($data) {
                        $q->where('base_salary', '<=', $data['base_salary'])
                            ->where('max_salary', '>=', $data['max_salary']);
                    });
            })->exists();
        // and max_salary should not be less than base_salary
        if ($data['max_salary'] < $data['base_salary']) {
            return [
                'status' => 'error',
                'message' => 'The max salary must be greater than or equal to the base salary.',
            ];
        }

        if ($overlaps) {
            return [
                'status' => 'error',
                'message' => 'The provided salary range overlaps with an existing pay grade.',
            ];
        }
        return [
            'status' => 'success',
            'message' => 'Pay grade validation passed.',
        ];
    }

    protected function createPayGrade(Request $request)
    {
        $payGrade = PayGrade::create($request->all());
        $payGrade->recordEvent('add', $request->all());
        return $payGrade;
    }

    protected function updatePayGrade(Request $request, PayGrade $payGrade)
    {
        $payGrade->update($request->all());
        $payGrade->recordEvent('update', $request->all());
        return $payGrade;
    }
}
