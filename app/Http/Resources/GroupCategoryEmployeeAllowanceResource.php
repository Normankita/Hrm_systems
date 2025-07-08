<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupCategoryEmployeeAllowanceResource extends JsonResource
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
            'amount' => $this->amount,
            'effective_from' => $this->effective_from,
            'isActive' => $this->isActive,
            'group_employee' => $this->group_employee_pivot_details,
            'group_allowance' => $this->group_allowance_pivot_details,
            'frequency' => $this->frequency
        ];
    }
}
