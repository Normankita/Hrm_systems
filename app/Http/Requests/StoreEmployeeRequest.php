<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'department_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'unique:users,email',
            'phone_number' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'national_id' => 'required|unique:employees,national_id',
            'marital_status' => 'required',
            'residential_address' => '',
            'tin_number' => 'required|unique:employees,tin_number',
            'employee_type' => 'required',
            'date_of_hire' => 'required|date',
            'pay_grade_id' => 'required',
            'passport_photo' => '',
            'tin_document' => '',
            'national_id_document' => '',
            'cv_document' => '',
            'certificates.*' => '',
        ];
    }

    /**
     * 
     * custom validator for checking age at hire
     * @param mixed $validator
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $dob = $this->input('date_of_birth');
            $hireDate = $this->input('date_of_hire');

            if ($dob && $hireDate) {
                $dob = Carbon::parse($dob);
                $hire = Carbon::parse($hireDate);
                $ageAtHire = $dob->diffInYears($hire);

                if ($ageAtHire < 18) {
                    $validator->errors()->add('date_of_birth', 'The employee must be at least 18 years old at the time of hire.');
                }
            }
        });
    }
}
