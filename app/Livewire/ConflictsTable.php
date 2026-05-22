<?php

namespace App\Livewire;

use App\Models\EmployeeConflict;
use Livewire\Component;
use Livewire\WithPagination;

class ConflictsTable extends Component
{
    use WithPagination;

    public ?int $employeeId = null;
    public bool $allowManage = true;
    public bool $personalMode = false;
    public bool $requirePermission = false;

    public string $search = '';
    public string $statusFilter = '';

    protected $listeners = ['conflictSaved' => '$refresh'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $conflicts = EmployeeConflict::with(['employee', 'otherEmployee', 'documents'])
            ->when($this->employeeId, function ($q) {
                $q->where(function ($q) {
                    $q->where('employee_id', $this->employeeId)
                        ->orWhere('other_employee_id', $this->employeeId);
                });
            })
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
