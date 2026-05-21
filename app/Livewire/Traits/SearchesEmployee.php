<?php

namespace App\Livewire\Traits;

use App\Models\Employee;

trait SearchesEmployee
{
    public ?string $employee_name = null;
    public ?int $employee_id = null;
    public array $employees = [];

    public function searchEmployee(): void
    {
        $query = Employee::query()->orderBy('full_name');

        if (strlen((string) $this->employee_name) >= 1) {
            $query->where('full_name', 'LIKE', '%' . $this->employee_name . '%');
        }

        $this->employees = $query->limit(10)->get()->all();
    }

    public function selectEmployee(int $id): void
    {
        $employee = Employee::find($id);

        if ($employee) {
            $this->employee_id = $employee->id;
            $this->employee_name = $employee->full_name;
            $this->employees = [];
        }
    }

    protected function resetEmployeeSearch(): void
    {
        $this->employee_name = null;
        $this->employee_id = null;
        $this->employees = [];
    }
}
