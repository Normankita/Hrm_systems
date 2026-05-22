<?php

namespace App\Livewire;

use App\Models\EmployeeComplaint;
use App\Models\EmployeeConflict;
use App\Models\EmployeeDiscipline;
use App\Models\EmployeeRelationResolution;
use Livewire\Component;
use Livewire\WithPagination;

class ResolutionsTable extends Component
{
    use WithPagination;

    public ?int $employeeId = null;
    public bool $allowManage = true;
    public bool $personalMode = false;
    public bool $requirePermission = false;

    public string $search = '';
    public string $statusFilter = '';

    protected $listeners = ['resolutionSaved' => '$refresh'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $resolutions = EmployeeRelationResolution::with(['resolvable', 'resolver', 'documents'])
            ->when($this->employeeId, function ($q) {
                $employeeId = $this->employeeId;
                $q->where(function ($q) use ($employeeId) {
                    $q->whereHasMorph('resolvable', [EmployeeComplaint::class], fn ($q) => $q->where('employee_id', $employeeId))
                        ->orWhereHasMorph('resolvable', [EmployeeDiscipline::class], fn ($q) => $q->where('employee_id', $employeeId))
                        ->orWhereHasMorph('resolvable', [EmployeeConflict::class], function ($q) use ($employeeId) {
                            $q->where('employee_id', $employeeId)
                                ->orWhere('other_employee_id', $employeeId);
                        });
                });
            })
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
