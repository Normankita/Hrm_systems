<?php

namespace App\Livewire;

use App\Models\EmployeeDiscipline;
use Livewire\Component;
use Livewire\WithPagination;

class DisciplinesTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    protected $listeners = ['disciplineSaved' => '$refresh'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $disciplines = EmployeeDiscipline::with(['employee', 'issuer'])
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('reference_number', 'like', '%' . $this->search . '%')
                    ->orWhere('action_type', 'like', '%' . $this->search . '%')
                    ->orWhereHas('employee', fn ($eq) => $eq->where('full_name', 'like', '%' . $this->search . '%'));
            }))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->orderByDesc('discipline_date')
            ->paginate(10);

        return view('livewire.disciplines-table', compact('disciplines'));
    }
}
