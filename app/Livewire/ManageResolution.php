<?php

namespace App\Livewire;

use App\Models\EmployeeComplaint;
use App\Models\EmployeeConflict;
use App\Models\EmployeeDiscipline;
use App\Models\EmployeeRelationResolution;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ManageResolution extends Component
{
    public ?int $resolutionId = null;
    public bool $showModal = false;
    public ?string $reference_number = null;
    public string $case_key = '';
    public string $resolvable_type = '';
    public ?int $resolvable_id = null;
    public string $title = '';
    public string $summary = '';
    public string $action_taken = '';
    public string $status = 'Open';
    public ?string $resolved_at = null;

    protected function rules(): array
    {
        return [
            'resolvable_type' => 'required|string',
            'resolvable_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'summary' => 'required|string|max:5000',
            'action_taken' => 'nullable|string|max:5000',
            'status' => 'required|string',
            'resolved_at' => 'nullable|date',
        ];
    }

    #[On('openResolutionModal')]
    public function openModal(): void
    {
        $this->resolutionId = null;
        $this->resetForm();
        $this->showModal = true;
        $this->dispatch('show-modal', modalId: 'manageResolutionModal');
    }

    #[On('editResolution')]
    public function openEdit(int $id): void
    {
        $record = EmployeeRelationResolution::findOrFail($id);
        $this->resolutionId = $record->id;
        $this->reference_number = $record->reference_number;
        $this->resolvable_type = $record->resolvable_type;
        $this->resolvable_id = $record->resolvable_id;
        $this->case_key = $record->resolvable_type . '|' . $record->resolvable_id;
        $this->title = $record->title;
        $this->summary = $record->summary;
        $this->action_taken = $record->action_taken ?? '';
        $this->status = $record->status;
        $this->resolved_at = $record->resolved_at?->format('Y-m-d');
        $this->showModal = true;
        $this->dispatch('show-modal', modalId: 'manageResolutionModal');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->dispatch('hide-modal', modalId: 'manageResolutionModal');
    }

    public function updatedCaseKey(): void
    {
        if (str_contains($this->case_key, '|')) {
            [$type, $id] = explode('|', $this->case_key, 2);
            $this->resolvable_type = $type;
            $this->resolvable_id = (int) $id;
        }
    }

    public function save(): void
    {
        $this->updatedCaseKey();
        $this->validate();
        $userId = Auth::id();

        $data = [
            'resolvable_type' => $this->resolvable_type,
            'resolvable_id' => $this->resolvable_id,
            'title' => $this->title,
            'summary' => $this->summary,
            'action_taken' => $this->action_taken ?: null,
            'status' => $this->status,
            'resolved_at' => $this->resolved_at ?: null,
            'resolved_by' => $this->resolved_at ? $userId : null,
        ];

        if ($this->resolutionId) {
            EmployeeRelationResolution::findOrFail($this->resolutionId)->update($data);
            session()->flash('success', 'Resolution updated successfully.');
        } else {
            EmployeeRelationResolution::create([
                ...$data,
                'reference_number' => EmployeeRelationResolution::nextReferenceNumber('RES'),
                'created_by' => $userId,
            ]);
            session()->flash('success', 'Resolution recorded successfully.');
        }

        if (in_array($this->status, ['Resolved', 'Closed'])) {
            $this->updateCaseStatus();
        }

        $this->dispatch('resolutionSaved');
        $this->closeModal();
        $this->resetForm();
    }

    private function updateCaseStatus(): void
    {
        $model = $this->resolvable_type::find($this->resolvable_id);
        if ($model) {
            $model->update(['status' => $this->status === 'Closed' ? 'Closed' : 'Resolved']);
        }
    }

    public function getOpenCasesProperty(): array
    {
        $cases = [];

        foreach (EmployeeComplaint::whereNotIn('status', ['Closed'])->orderByDesc('complaint_date')->limit(50)->get() as $c) {
            $cases[] = ['type' => EmployeeComplaint::class, 'id' => $c->id, 'label' => "Complaint {$c->reference_number} — {$c->subject}"];
        }
        foreach (EmployeeDiscipline::whereNotIn('status', ['Closed'])->orderByDesc('discipline_date')->limit(50)->get() as $c) {
            $cases[] = ['type' => EmployeeDiscipline::class, 'id' => $c->id, 'label' => "Discipline {$c->reference_number} — {$c->action_type}"];
        }
        foreach (EmployeeConflict::whereNotIn('status', ['Closed'])->orderByDesc('conflict_date')->limit(50)->get() as $c) {
            $cases[] = ['type' => EmployeeConflict::class, 'id' => $c->id, 'label' => "Conflict {$c->reference_number} — {$c->subject}"];
        }

        return $cases;
    }

    private function resetForm(): void
    {
        $this->reset(['reference_number', 'case_key', 'resolvable_type', 'resolvable_id', 'title', 'summary', 'action_taken', 'status', 'resolved_at', 'resolutionId']);
        $this->status = 'Open';
    }

    public function render()
    {
        return view('livewire.manage-resolution', [
            'openCases' => $this->openCases,
        ]);
    }
}
