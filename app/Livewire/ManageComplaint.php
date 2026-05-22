<?php

namespace App\Livewire;

use App\Enums\RelationCaseStatusEnum;
use App\Enums\RelationSeverityEnum;
use App\Http\Utils\Traits\AuthorizesRelationAccess;
use App\Livewire\Traits\ManagesRelationDocuments;
use App\Livewire\Traits\SearchesEmployee;
use App\Models\EmployeeComplaint;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ManageComplaint extends Component
{
    use AuthorizesRelationAccess, ManagesRelationDocuments, SearchesEmployee;

    public ?int $complaintId = null;
    public bool $showModal = false;
    public bool $lockEmployee = false;
    public bool $viewOnly = false;
    public bool $personalMode = false;
    public string $downloadRoute = 'admin.employee-relations.download';

    public ?string $reference_number = null;
    public string $subject = '';
    public string $description = '';
    public ?string $complaint_date = null;
    public string $severity = 'Medium';
    public string $status = 'Open';

    protected function rules(): array
    {
        return array_merge([
            'employee_name' => 'required|string|max:255',
            'employee_id' => 'required|exists:employees,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'complaint_date' => 'required|date',
            'severity' => 'required|string',
            'status' => 'required|string',
        ], $this->documentValidationRules());
    }

    #[On('openComplaintModal')]
    public function openModal(): void
    {
        $this->complaintId = null;
        $this->viewOnly = false;
        $this->lockEmployee = false;
        $this->resetForm();
        $this->complaint_date = now()->format('Y-m-d');
        $this->showModal = true;
        $this->dispatch('show-modal', modalId: 'manageComplaintModal');
    }

    #[On('openMyComplaintModal')]
    public function openMyComplaint(): void
    {
        $employee = auth()->user()->employee;
        if (! $employee) {
            return;
        }

        $this->openModal();
        $this->lockEmployee = true;
        $this->employee_id = $employee->id;
        $this->employee_name = $employee->full_name;
        $this->status = 'Open';
    }

    #[On('viewComplaint')]
    public function viewComplaint(int $id): void
    {
        $this->openEdit($id);
        $this->viewOnly = true;
    }

    #[On('editComplaint')]
    public function openEdit(int $id): void
    {
        $complaint = EmployeeComplaint::with(['employee', 'documents'])->findOrFail($id);
        $this->complaintId = $complaint->id;
        $this->viewOnly = false;
        $this->lockEmployee = false;
        if ($this->personalMode) {
            $this->authorizeOwnRelationModel($complaint);
            $this->lockEmployee = true;
            if ($complaint->status !== 'Open') {
                $this->viewOnly = true;
            }
        }
        $this->reference_number = $complaint->reference_number;
        $this->employee_id = $complaint->employee_id;
        $this->employee_name = $complaint->employee?->full_name;
        $this->subject = $complaint->subject;
        $this->description = $complaint->description;
        $this->complaint_date = $complaint->complaint_date->format('Y-m-d');
        $this->severity = $complaint->severity;
        $this->status = $complaint->status;
        $this->employees = [];
        $this->loadRelationDocuments($complaint);
        $this->showModal = true;
        $this->dispatch('show-modal', modalId: 'manageComplaintModal');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->dispatch('hide-modal', modalId: 'manageComplaintModal');
    }

    public function save(): void
    {
        if ($this->viewOnly) {
            return;
        }

        $this->validate();
        $userId = Auth::id();

        if ($this->complaintId) {
            $complaint = EmployeeComplaint::findOrFail($this->complaintId);
            if ($this->personalMode) {
                $this->authorizeOwnRelationModel($complaint);
            }
            $update = [
                'subject' => $this->subject,
                'description' => $this->description,
                'complaint_date' => $this->complaint_date,
                'severity' => $this->severity,
            ];
            if ($this->personalMode) {
                $update['employee_id'] = $complaint->employee_id;
            } else {
                $update['employee_id'] = $this->employee_id;
                $update['status'] = $this->status;
            }
            $complaint->update($update);
            $this->syncRelationDocuments($complaint);
            session()->flash('success', 'Complaint updated successfully.');
        } else {
            $complaint = EmployeeComplaint::create([
                'reference_number' => EmployeeComplaint::nextReferenceNumber('CMP'),
                'employee_id' => $this->employee_id,
                'subject' => $this->subject,
                'description' => $this->description,
                'complaint_date' => $this->complaint_date,
                'severity' => $this->severity,
                'status' => $this->status,
                'reported_by' => $userId,
                'created_by' => $userId,
            ]);
            $this->syncRelationDocuments($complaint);
            session()->flash('success', 'Complaint registered successfully.');
        }

        $this->dispatch('complaintSaved');
        $this->closeModal();
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['reference_number', 'subject', 'description', 'complaint_date', 'severity', 'status', 'complaintId', 'viewOnly', 'lockEmployee']);
        $this->severity = 'Medium';
        $this->status = 'Open';
        $this->resetEmployeeSearch();
        $this->resetRelationDocuments();
    }

    public function render()
    {
        return view('livewire.manage-complaint');
    }
}
