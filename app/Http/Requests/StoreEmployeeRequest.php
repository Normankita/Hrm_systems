<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'date_of_birth' => 'required',
            'national_id' => 'unique:employees,national_id',
            'marital_status' => 'required',
            'residential_address' => '',
            'tin_number' => 'unique:employees,tin_number',
            'employee_type' => 'required',
            'date_of_hire' => 'required|date',
            'pay_grade_id'=>'required',
            'passport_photo' => '',
            'tin_document'=>'',
            'national_id_document'=>'',
            'cv_document' => '',
            'certificates.*' => '',
        ];
    }

}
