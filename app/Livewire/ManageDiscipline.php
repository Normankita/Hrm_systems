<?php

namespace App\Livewire;

use App\Livewire\Traits\SearchesEmployee;
use App\Models\EmployeeDiscipline;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ManageDiscipline extends Component
{
    use SearchesEmployee;

    public ?int $disciplineId = null;
    public bool $showModal = false;
    public ?string $reference_number = null;
    public string $action_type = '';
    public string $description = '';
    public ?string $discipline_date = null;
    public string $status = 'Open';

    protected function rules(): array
    {
        return [
            'employee_name' => 'required|string|max:255',
            'employee_id' => 'required|exists:employees,id',
            'action_type' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'discipline_date' => 'required|date',
            'status' => 'required|string',
        ];
    }

    #[On('openDisciplineModal')]
    public function openModal(): void
    {
        $this->disciplineId = null;
        $this->resetForm();
        $this->discipline_date = now()->format('Y-m-d');
        $this->showModal = true;
        $this->dispatch('show-modal', modalId: 'manageDisciplineModal');
    }

    #[On('editDiscipline')]
    public function openEdit(int $id): void
    {
        $record = EmployeeDiscipline::with('employee')->findOrFail($id);
        $this->disciplineId = $record->id;
        $this->reference_number = $record->reference_number;
        $this->employee_id = $record->employee_id;
        $this->employee_name = $record->employee?->full_name;
        $this->action_type = $record->action_type;
        $this->description = $record->description;
        $this->discipline_date = $record->discipline_date->format('Y-m-d');
        $this->status = $record->status;
        $this->employees = [];
        $this->showModal = true;
        $this->dispatch('show-modal', modalId: 'manageDisciplineModal');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->dispatch('hide-modal', modalId: 'manageDisciplineModal');
    }

    public function save(): void
    {
        $this->validate();
        $userId = Auth::id();

        if ($this->disciplineId) {
            EmployeeDiscipline::findOrFail($this->disciplineId)->update([
                'employee_id' => $this->employee_id,
                'action_type' => $this->action_type,
                'description' => $this->description,
                'discipline_date' => $this->discipline_date,
                'status' => $this->status,
            ]);
            session()->flash('success', 'Discipline record updated successfully.');
        } else {
            EmployeeDiscipline::create([
                'reference_number' => EmployeeDiscipline::nextReferenceNumber('DSC'),
                'employee_id' => $this->employee_id,
                'action_type' => $this->action_type,
                'description' => $this->description,
                'discipline_date' => $this->discipline_date,
                'status' => $this->status,
                'issued_by' => $userId,
                'created_by' => $userId,
            ]);
            session()->flash('success', 'Discipline record created successfully.');
        }

        $this->dispatch('disciplineSaved');
        $this->closeModal();
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['reference_number', 'action_type', 'description', 'discipline_date', 'status', 'disciplineId']);
        $this->status = 'Open';
        $this->resetEmployeeSearch();
    }

    public function render()
    {
        return view('livewire.manage-discipline');
    }
}
