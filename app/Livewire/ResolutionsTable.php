<?php

namespace App\Livewire;

use App\Models\EmployeeRelationResolution;
use Livewire\Component;
use Livewire\WithPagination;

class ResolutionsTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    protected $listeners = ['resolutionSaved' => '$refresh'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $resolutions = EmployeeRelationResolution::with(['resolvable', 'resolver'])
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('reference_number', 'like', '%' . $this->search . '%')
                    ->orWhere('title', 'like', '%' . $this->search . '%');
            }))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.resolutions-table', compact('resolutions'));
    }
}
