<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'salary' => $this->salary,
            'company_id' => $this->company_id,
            'gender' => $this->gender,
            'national_id' => $this->national_id,
            'employee_type' => $this->employee_type,
            'date_of_hire' => $this->date_of_hire,
        ];
    }
}
