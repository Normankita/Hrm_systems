<?php

namespace App\Livewire;

use App\Models\EmployeeContract;
use Livewire\Attributes\On;
use Livewire\Component;

class ViewContractView extends Component
{
    public EmployeeContract $contract;

    #[On('contractUpdated')]
    public function refreshContract(): void
    {
        $this->contract = EmployeeContract::with([
            'employee.department',
            'employee.designation',
            'employee.company',
            'contractFiles',
            'createdBy',
        ])->findOrFail($this->contract->id);
    }

    public function render()
    {
        return view('livewire.view-contract-view');
    }
}
