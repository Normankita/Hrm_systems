<?php

namespace App\Livewire;

use App\Enums\RelationCaseStatusEnum;
use App\Enums\RelationSeverityEnum;
use App\Livewire\Traits\SearchesEmployee;
use App\Models\EmployeeComplaint;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ManageComplaint extends Component
{
    use SearchesEmployee;

    public ?int $complaintId = null;
    public bool $showModal = false;
    public ?string $reference_number = null;
    public string $subject = '';
    public string $description = '';
    public ?string $complaint_date = null;
    public string $severity = 'Medium';
    public string $status = 'Open';

    protected function rules(): array
    {
        return [
            'employee_name' => 'required|string|max:255',
            'employee_id' => 'required|exists:employees,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'complaint_date' => 'required|date',
            'severity' => 'required|string',
            'status' => 'required|string',
        ];
    }

    #[On('openComplaintModal')]
    public function openModal(): void
    {
        $this->complaintId = null;
        $this->resetForm();
        $this->complaint_date = now()->format('Y-m-d');
        $this->showModal = true;
        $this->dispatch('show-modal', modalId: 'manageComplaintModal');
    }

    #[On('editComplaint')]
    public function openEdit(int $id): void
    {
        $complaint = EmployeeComplaint::with('employee')->findOrFail($id);
        $this->complaintId = $complaint->id;
        $this->reference_number = $complaint->reference_number;
        $this->employee_id = $complaint->employee_id;
        $this->employee_name = $complaint->employee?->full_name;
        $this->subject = $complaint->subject;
        $this->description = $complaint->description;
        $this->complaint_date = $complaint->complaint_date->format('Y-m-d');
        $this->severity = $complaint->severity;
        $this->status = $complaint->status;
        $this->employees = [];
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
        $this->validate();
        $userId = Auth::id();

        if ($this->complaintId) {
            $complaint = EmployeeComplaint::findOrFail($this->complaintId);
            $complaint->update([
                'employee_id' => $this->employee_id,
                'subject' => $this->subject,
                'description' => $this->description,
                'complaint_date' => $this->complaint_date,
                'severity' => $this->severity,
                'status' => $this->status,
            ]);
            session()->flash('success', 'Complaint updated successfully.');
        } else {
            EmployeeComplaint::create([
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
            session()->flash('success', 'Complaint registered successfully.');
        }

        $this->dispatch('complaintSaved');
        $this->closeModal();
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['reference_number', 'subject', 'description', 'complaint_date', 'severity', 'status', 'complaintId']);
        $this->severity = 'Medium';
        $this->status = 'Open';
        $this->resetEmployeeSearch();
    }

    public function render()
    {
        return view('livewire.manage-complaint');
    }
}
