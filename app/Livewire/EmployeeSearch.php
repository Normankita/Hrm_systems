<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee; // your employee model

class EmployeeSearch extends Component
{
    public $query = '';
    public $employees = [];
    public $selectedEmployee = null;

    public $selectedEmployeeId = null; // to keep track of selected employee ID

    public function updatedQuery()
    {
        $this->employees = Employee::where(
            'full_name',
            'like',
            '%' . $this->query . '%'
        )
            ->get();
    }

    public function selectEmployee($id)
    {
        $employee = Employee::find($id);
        if ($employee) {
            $this->selectedEmployee = $employee;
            $this->selectedEmployeeId = $employee->id; // keep ID
            $this->query = $employee->full_name;
            $this->employees = []; // clear dropdown after selection
        }
    }

    public function render()
    {
        return view('livewire.employee-search');
    }
}
