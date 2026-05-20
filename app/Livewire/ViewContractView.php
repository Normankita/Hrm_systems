<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmployeeContract;

class ViewContractView extends Component
{
    public EmployeeContract $contract;

    public function render()
    {
        return view('livewire.view-contract-view');
    }
}
