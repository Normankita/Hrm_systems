<?php

namespace App\Livewire;

use App\Models\EmployeeConflict;
use Livewire\Component;
use Livewire\WithPagination;

class ConflictsTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    protected $listeners = ['conflictSaved' => '$refresh'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $conflicts = EmployeeConflict::with(['employee', 'otherEmployee'])
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('reference_number', 'like', '%' . $this->search . '%')
                    ->orWhere('subject', 'like', '%' . $this->search . '%')
                    ->orWhereHas('employee', fn ($eq) => $eq->where('full_name', 'like', '%' . $this->search . '%'));
            }))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->orderByDesc('conflict_date')
            ->paginate(10);

        return view('livewire.conflicts-table', compact('conflicts'));
    }
}
