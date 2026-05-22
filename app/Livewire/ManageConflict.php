<?php

namespace App\Livewire;

use App\Http\Utils\Traits\AuthorizesRelationAccess;
use App\Livewire\Traits\ManagesRelationDocuments;
use App\Livewire\Traits\SearchesEmployee;
use App\Models\Employee;
use App\Models\EmployeeConflict;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ManageConflict extends Component
{
    use AuthorizesRelationAccess, ManagesRelationDocuments, SearchesEmployee;

    public ?int $conflictId = null;
    public bool $showModal = false;
    public bool $lockEmployee = false;
    public bool $viewOnly = false;
    public bool $personalMode = false;
    public string $downloadRoute = 'admin.employee-relations.download';

    public ?string $reference_number = null;
    public string $subject = '';
    public string $description = '';
    public ?string $conflict_date = null;
    public string $severity = 'Medium';
    public string $status = 'Open';

    public ?string $other_employee_name = null;
    public ?int $other_employee_id = null;
    public array $otherEmployees = [];

    protected function rules(): array
    {
        return array_merge([
            'employee_name' => 'required|string|max:255',
            'employee_id' => 'required|exists:employees,id',
            'other_employee_id' => 'nullable|exists:employees,id|different:employee_id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'conflict_date' => 'required|date',
            'severity' => 'required|string',
            'status' => 'required|string',
        ], $this->documentValidationRules());
    }

    public function searchOtherEmployee(): void
    {
        $query = Employee::query()->orderBy('full_name');
        if (strlen((string) $this->other_employee_name) >= 1) {
            $query->where('full_name', 'LIKE', '%' . $this->other_employee_name . '%');
        }
        $this->otherEmployees = $query->limit(10)->get()->all();
    }

    public function selectOtherEmployee(int $id): void
    {
        $employee = Employee::find($id);
        if ($employee) {
            $this->other_employee_id = $employee->id;
            $this->other_employee_name = $employee->full_name;
            $this->otherEmployees = [];
        }
    }

    #[On('openConflictModal')]
    public function openModal(): void
    {
        $this->conflictId = null;
        $this->viewOnly = false;
        $this->lockEmployee = false;
        $this->resetForm();
        $this->conflict_date = now()->format('Y-m-d');
        $this->showModal = true;
        $this->dispatch('show-modal', modalId: 'manageConflictModal');
    }

    #[On('openMyConflictModal')]
    public function openMyConflict(): void
    {
        $employee = auth()->user()->employee;
        if (! $employee) {
            return;
        }
        $this->openModal();
        $this->lockEmployee = true;
        $this->employee_id = $employee->id;
        $this->employee_name = $employee->full_name;
    }

    #[On('viewConflict')]
    public function viewConflict(int $id): void
    {
        $this->openEdit($id);
        $this->viewOnly = true;
    }

    #[On('editConflict')]
    public function openEdit(int $id): void
    {
        $record = EmployeeConflict::with(['employee', 'otherEmployee', 'documents'])->findOrFail($id);
        $this->conflictId = $record->id;
        $this->viewOnly = false;
        $this->lockEmployee = false;
        if ($this->personalMode) {
            $this->authorizeOwnRelationModel($record);
            $this->lockEmployee = true;
            if ($record->status !== 'Open') {
                $this->viewOnly = true;
            }
        }
        $this->reference_number = $record->reference_number;
        $this->employee_id = $record->employee_id;
        $this->employee_name = $record->employee?->full_name;
        $this->other_employee_id = $record->other_employee_id;
        $this->other_employee_name = $record->otherEmployee?->full_name;
        $this->subject = $record->subject;
        $this->description = $record->description;
        $this->conflict_date = $record->conflict_date->format('Y-m-d');
        $this->severity = $record->severity;
        $this->status = $record->status;
        $this->employees = [];
        $this->otherEmployees = [];
        $this->loadRelationDocuments($record);
        $this->showModal = true;
        $this->dispatch('show-modal', modalId: 'manageConflictModal');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->dispatch('hide-modal', modalId: 'manageConflictModal');
    }

    public function save(): void
    {
        if ($this->viewOnly) {
            return;
        }

        $this->validate();
        $userId = Auth::id();
        $data = [
            'employee_id' => $this->employee_id,
            'other_employee_id' => $this->other_employee_id,
            'subject' => $this->subject,
            'description' => $this->description,
            'conflict_date' => $this->conflict_date,
            'severity' => $this->severity,
            'status' => $this->lockEmployee ? 'Open' : $this->status,
        ];

        if ($this->conflictId) {
            $record = EmployeeConflict::findOrFail($this->conflictId);
            if ($this->personalMode) {
                $this->authorizeOwnRelationModel($record);
            }
            if ($this->lockEmployee || $this->personalMode) {
                unset($data['status']);
                $data['employee_id'] = $record->employee_id;
            }
            $record->update($data);
            $this->syncRelationDocuments($record);
            session()->flash('success', 'Conflict updated successfully.');
        } else {
            $record = EmployeeConflict::create([
                ...$data,
                'reference_number' => EmployeeConflict::nextReferenceNumber('CNF'),
                'created_by' => $userId,
            ]);
            $this->syncRelationDocuments($record);
            session()->flash('success', 'Conflict registered successfully.');
        }

        $this->dispatch('conflictSaved');
        $this->closeModal();
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['reference_number', 'subject', 'description', 'conflict_date', 'severity', 'status', 'conflictId', 'other_employee_id', 'other_employee_name', 'otherEmployees', 'viewOnly', 'lockEmployee']);
        $this->severity = 'Medium';
        $this->status = 'Open';
        $this->resetEmployeeSearch();
        $this->resetRelationDocuments();
    }

    public function render()
    {
        return view('livewire.manage-conflict');
    }
}
