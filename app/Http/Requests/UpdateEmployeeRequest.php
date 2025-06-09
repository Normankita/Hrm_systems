<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
            'role_id' => 'required',
            'department_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'gender' => 'required',
            'national_id' => 'required',
            'marital_status' => 'required',
            'residential_address' => '',
            'tin_number' => '',
            'employee_type' => 'required',
            'date_of_birth' => 'required|date',
            'date_of_hire' => 'required|date',
            'passport_photo' => '',
            'tin_document' => '',
            'national_id_document' => '',
            'cv_document' => '',
            'certificates.*' => '',
        ];
    }
}
