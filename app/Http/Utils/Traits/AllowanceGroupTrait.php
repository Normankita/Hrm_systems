<?php

namespace App\Http\Utils\Traits;

use App\Models\AllowanceGroup;
use App\Models\Employee;

trait AllowanceGroupTrait
{
    private function getEmployeeForAdditionSelection(AllowanceGroup $group)
    {
        // Reload the group with employees
        $group = AllowanceGroup::with('employees')->find($group->id);

        // Filter employees that are active in the pivot
        $activeGroupEmployees = $group->employees->filter(function ($employee) {
            return $employee->pivot->isActive;
        });

        // Get all employees not already actively in the group
        $employees = Employee::whereNotIn(
            'id',
            $activeGroupEmployees->pluck('id')->toArray()
        )->get();

        return $employees;
    }

    

}
