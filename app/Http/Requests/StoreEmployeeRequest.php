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
            'role_id' => '',
            'department_id' => '',
            'company_id' => '',
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'phone' => '',
            'gender' => '',
            'date_of_birth' => '',
            'phone_number' => '',
            'national_id' => '',
            'marital_status' => '',
            'residential_address' => '',
            'tin_number' => '',
            'employee_type' => '',
            'date_of_hire' => ''
        ];
    }
}
