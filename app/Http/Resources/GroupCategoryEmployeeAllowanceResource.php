<?php

namespace App\Http\Resources;

use App\Models\AllowanceGroup;
use App\Models\AllowanceGroupAllowancePivot;
use App\Models\AllowanceGroupEmployeePivot;
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
        $group = AllowanceGroup::find($this->group_employee_pivot->allowance_group_id);
        $employee = new AllowanceGroupEmployeePivotResource(
            AllowanceGroupEmployeePivot::getRealDetails($this->group_employee_pivot->id));
        $employee = $employee->resolve();
        $allowance = new AllowanceGroupAllowancePivotResource(
            AllowanceGroupAllowancePivot::getRealDetails($this->group_allowance_pivot->id));
        $allowance = $allowance->resolve();
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'effective_from' => $this->effective_from,
            'isActive' => $this->isActive,
            'employee' => $employee['employee'],
            'allowance' => $allowance['allowance'],
            'group' => $group,
            'frequency' => $this->frequency
        ];
    }
}
