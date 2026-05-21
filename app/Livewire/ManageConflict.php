<?php

namespace App\Livewire;

use App\Livewire\Traits\SearchesEmployee;
use App\Models\Employee;
use App\Models\EmployeeConflict;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ManageConflict extends Component
{
    use SearchesEmployee;

    public ?int $conflictId = null;
    public bool $showModal = false;
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
        return [
            'employee_name' => 'required|string|max:255',
            'employee_id' => 'required|exists:employees,id',
            'other_employee_id' => 'nullable|exists:employees,id|different:employee_id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'conflict_date' => 'required|date',
            'severity' => 'required|string',
            'status' => 'required|string',
        ];
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
        $this->resetForm();
        $this->conflict_date = now()->format('Y-m-d');
        $this->showModal = true;
        $this->dispatch('show-modal', modalId: 'manageConflictModal');
    }

    #[On('editConflict')]
    public function openEdit(int $id): void
    {
        $record = EmployeeConflict::with(['employee', 'otherEmployee'])->findOrFail($id);
        $this->conflictId = $record->id;
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
        $this->validate();
        $userId = Auth::id();
        $data = [
            'employee_id' => $this->employee_id,
            'other_employee_id' => $this->other_employee_id,
            'subject' => $this->subject,
            'description' => $this->description,
            'conflict_date' => $this->conflict_date,
            'severity' => $this->severity,
            'status' => $this->status,
        ];

        if ($this->conflictId) {
            EmployeeConflict::findOrFail($this->conflictId)->update($data);
            session()->flash('success', 'Conflict updated successfully.');
        } else {
            EmployeeConflict::create([
                ...$data,
                'reference_number' => EmployeeConflict::nextReferenceNumber('CNF'),
                'created_by' => $userId,
            ]);
            session()->flash('success', 'Conflict registered successfully.');
        }

        $this->dispatch('conflictSaved');
        $this->closeModal();
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['reference_number', 'subject', 'description', 'conflict_date', 'severity', 'status', 'conflictId', 'other_employee_id', 'other_employee_name', 'otherEmployees']);
        $this->severity = 'Medium';
        $this->status = 'Open';
        $this->resetEmployeeSearch();
    }

    public function render()
    {
        return view('livewire.manage-conflict');
    }
}
