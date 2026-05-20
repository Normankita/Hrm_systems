<?php

namespace App\Livewire;

use Livewire\Component;

class EditContractModel extends Component
{

    public $showModal = false;

    public $files;

    public function openModal()
    {
        $this->showModal = true;

        // Dispatch browser event to show Bootstrap modal
        $this->dispatch('show-modal', modalId: 'editContractModel');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->dispatch('hide-modal', modalId: 'editContractModel');
    }

    public function render()
    {
        return view('livewire.edit-contract-model');
    }
}
