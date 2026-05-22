<?php

namespace App\Livewire;

use App\Http\Utils\Traits\AuthorizesRelationAccess;
use App\Livewire\Traits\ManagesRelationDocuments;
use App\Models\EmployeeComplaint;
use App\Models\EmployeeConflict;
use App\Models\EmployeeDiscipline;
use App\Models\EmployeeRelationResolution;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ManageResolution extends Component
{
    use AuthorizesRelationAccess, ManagesRelationDocuments;

    public ?int $resolutionId = null;
    public bool $showModal = false;
    public bool $viewOnly = false;
    public bool $personalMode = false;
    public ?int $employeeId = null;
    public string $downloadRoute = 'admin.employee-relations.download';

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
        return array_merge([
            'resolvable_type' => 'required|string',
            'resolvable_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'summary' => 'required|string|max:5000',
            'action_taken' => 'nullable|string|max:5000',
            'status' => 'required|string',
            'resolved_at' => 'nullable|date',
        ], $this->documentValidationRules());
    }

    #[On('openResolutionModal')]
    public function openModal(): void
    {
        $this->resolutionId = null;
        $this->viewOnly = false;
        $this->resetForm();
        $this->showModal = true;
        $this->dispatch('show-modal', modalId: 'manageResolutionModal');
    }

    #[On('viewResolution')]
    public function viewResolution(int $id): void
    {
        $this->openEdit($id);
        $this->viewOnly = true;
    }

    #[On('editResolution')]
    public function openEdit(int $id): void
    {
        $record = EmployeeRelationResolution::with('documents')->findOrFail($id);
        if ($this->personalMode) {
            $this->authorizeOwnRelationModel($record);
            $this->viewOnly = true;
        }
        $this->resolutionId = $record->id;
        if (! $this->personalMode) {
            $this->viewOnly = false;
        }
        $this->reference_number = $record->reference_number;
        $this->resolvable_type = $record->resolvable_type;
        $this->resolvable_id = $record->resolvable_id;
        $this->case_key = $record->resolvable_type . '|' . $record->resolvable_id;
        $this->title = $record->title;
        $this->summary = $record->summary;
        $this->action_taken = $record->action_taken ?? '';
        $this->status = $record->status;
        $this->resolved_at = $record->resolved_at?->format('Y-m-d');
        $this->loadRelationDocuments($record);
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
        if ($this->viewOnly) {
            return;
        }

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
            $record = EmployeeRelationResolution::findOrFail($this->resolutionId);
            $record->update($data);
            $this->syncRelationDocuments($record);
            session()->flash('success', 'Resolution updated successfully.');
        } else {
            $record = EmployeeRelationResolution::create([
                ...$data,
                'reference_number' => EmployeeRelationResolution::nextReferenceNumber('RES'),
                'created_by' => $userId,
            ]);
            $this->syncRelationDocuments($record);
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

        $complaintQuery = EmployeeComplaint::whereNotIn('status', ['Closed'])->orderByDesc('complaint_date')->limit(50);
        $disciplineQuery = EmployeeDiscipline::whereNotIn('status', ['Closed'])->orderByDesc('discipline_date')->limit(50);
        $conflictQuery = EmployeeConflict::whereNotIn('status', ['Closed'])->orderByDesc('conflict_date')->limit(50);

        if ($this->employeeId) {
            $complaintQuery->where('employee_id', $this->employeeId);
            $disciplineQuery->where('employee_id', $this->employeeId);
            $conflictQuery->where(function ($q) {
                $q->where('employee_id', $this->employeeId)
                    ->orWhere('other_employee_id', $this->employeeId);
            });
        }

        foreach ($complaintQuery->get() as $c) {
            $cases[] = ['type' => EmployeeComplaint::class, 'id' => $c->id, 'label' => "Complaint {$c->reference_number} — {$c->subject}"];
        }
        foreach ($disciplineQuery->get() as $c) {
            $cases[] = ['type' => EmployeeDiscipline::class, 'id' => $c->id, 'label' => "Discipline {$c->reference_number} — {$c->action_type}"];
        }
        foreach ($conflictQuery->get() as $c) {
            $cases[] = ['type' => EmployeeConflict::class, 'id' => $c->id, 'label' => "Conflict {$c->reference_number} — {$c->subject}"];
        }

        return $cases;
    }

    private function resetForm(): void
    {
        $this->reset(['reference_number', 'case_key', 'resolvable_type', 'resolvable_id', 'title', 'summary', 'action_taken', 'status', 'resolved_at', 'resolutionId', 'viewOnly']);
        $this->status = 'Open';
        $this->resetRelationDocuments();
    }

    public function render()
    {
        return view('livewire.manage-resolution', [
            'openCases' => $this->openCases,
        ]);
    }
}
