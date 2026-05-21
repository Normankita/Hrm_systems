<?php

namespace App\Livewire;

use Livewire\Component;

class EmployeeRelationsHub extends Component
{
    public string $activeTab = 'complaints';

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.employee-relations-hub');
    }
}
