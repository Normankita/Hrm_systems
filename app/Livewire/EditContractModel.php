<?php

namespace App\Livewire;

use App\Models\ContractFile;
use App\Models\ContractType;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\EmployeeContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditContractModel extends Component
{
    use WithFileUploads;

    public int $contractId;

    public bool $showModal = false;

    public ?string $contract_number = null;

    public ?string $employee_name = null;
    public ?int $employee_id = null;
    public array $employees = [];

    public ?string $contract_type = null;
    public ?string $start_date = null;
    public ?string $end_date = null;
    public ?string $probation_end_date = null;
    public ?string $signed_date = null;
    public ?string $work_location = null;
    public $basic_salary = null;
    public ?string $currency = 'TZS';
    public ?string $payment_frequency = null;
    public ?string $contract_status = 'active';
    public ?string $termination_reason = null;

    /** @var array<int, array{id: int, original_name: string}> */
    public array $existingFiles = [];

    /** @var array<int> */
    public array $filesToDelete = [];

    public array $files = [];

    protected function rules(): array
    {
        return [
            'employee_name' => 'required|string|max:255',
            'employee_id' => 'required|exists:employees,id',
            'contract_type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'probation_end_date' => 'nullable|date',
            'signed_date' => 'nullable|date',
            'work_location' => 'nullable|string|max:255',
            'basic_salary' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'payment_frequency' => 'nullable|string|max:50',
            'contract_status' => 'required|string|max:50',
            'termination_reason' => 'nullable|string|max:1000',
            'files.*' => 'file|mimes:pdf|max:10240',
        ];
    }

    public function mount(int $contractId): void
    {
        $this->contractId = $contractId;
    }

    public function openModal(): void
    {
        $this->loadContract();
        $this->showModal = true;
        $this->dispatch('show-modal', modalId: 'editContractModel');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->dispatch('hide-modal', modalId: 'editContractModel');
    }

    public function loadContract(): void
    {
        $contract = EmployeeContract::with(['contractFiles', 'employee'])
            ->findOrFail($this->contractId);

        $this->contract_number = $contract->contract_number;
        $this->employee_id = $contract->employee_id;
        $this->employee_name = $contract->employee?->full_name;
        $this->contract_type = $contract->contract_type;
        $this->start_date = $this->formatDateForInput($contract->start_date);
        $this->end_date = $this->formatDateForInput($contract->end_date);
        $this->probation_end_date = $this->formatDateForInput($contract->probation_end_date);
        $this->signed_date = $this->formatDateForInput($contract->signed_date);
        $this->work_location = $contract->work_location;
        $this->basic_salary = $contract->basic_salary;
        $this->currency = $contract->currency ?? 'TZS';
        $this->payment_frequency = $contract->payment_frequency;
        $this->contract_status = $contract->contract_status ?? 'active';
        $this->termination_reason = $contract->termination_reason;

        $this->existingFiles = $contract->contractFiles
            ->map(fn (ContractFile $file) => [
                'id' => $file->id,
                'original_name' => $file->original_name,
            ])
            ->values()
            ->all();

        $this->filesToDelete = [];
        $this->files = [];
        $this->employees = [];
    }

    public function searchEmployee(): void
    {
        $query = Employee::query()->orderBy('full_name');

        if (strlen((string) $this->employee_name) >= 1) {
            $query->where('full_name', 'LIKE', '%' . $this->employee_name . '%');
        }

        $this->employees = $query->limit(10)->get()->all();
    }

    public function selectEmployee(int $id): void
    {
        $employee = Employee::find($id);

        if ($employee) {
            $this->employee_id = $employee->id;
            $this->employee_name = $employee->full_name;
            $this->employees = [];
        }
    }

    public function markFileForDeletion(int $fileId): void
    {
        if (! in_array($fileId, $this->filesToDelete, true)) {
            $this->filesToDelete[] = $fileId;
        }
    }

    public function unmarkFileForDeletion(int $fileId): void
    {
        $this->filesToDelete = array_values(
            array_filter($this->filesToDelete, fn (int $id) => $id !== $fileId)
        );
    }

    public function save(): void
    {
        $this->validate();

        $contract = EmployeeContract::findOrFail($this->contractId);
        $employee = Employee::find($this->employee_id);

        if (! $employee) {
            session()->flash('error', 'Selected employee not found.');
            return;
        }

        $remainingFiles = collect($this->existingFiles)
            ->pluck('id')
            ->diff($this->filesToDelete)
            ->count();

        if ($remainingFiles === 0 && empty($this->files)) {
            session()->flash('error', 'At least one contract document is required.');
            return;
        }

        DB::beginTransaction();

        try {
            $authUser = Auth::user();
            $contractType = ContractType::getOrCreateContractType($this->contract_type);
            $role = $employee->role();
            $designationId = Designation::getOrCreateDesignation(
                $role->name,
                $employee->department_id
            )->id;

            $contract->update([
                'employee_id' => $this->employee_id,
                'contract_type' => $this->contract_type,
                'contract_type_id' => $contractType->id,
                'department_id' => $employee->department_id,
                'designation_id' => $designationId,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date ?: null,
                'probation_end_date' => $this->probation_end_date ?: null,
                'signed_date' => $this->signed_date ?: null,
                'work_location' => $this->work_location,
                'basic_salary' => $this->basic_salary,
                'currency' => $this->currency,
                'payment_frequency' => $this->payment_frequency,
                'contract_status' => $this->contract_status,
                'termination_reason' => $this->termination_reason,
            ]);

            foreach ($this->filesToDelete as $fileId) {
                $contractFile = ContractFile::where('employee_contract_id', $contract->id)
                    ->where('id', $fileId)
                    ->first();

                if ($contractFile) {
                    Storage::disk('local')->delete($contractFile->file_path);
                    $contractFile->delete();
                }
            }

            if (! empty($this->files)) {
                foreach ($this->files as $file) {
                    $fileName = uniqid('contract_') . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('contracts', $fileName, 'local');

                    ContractFile::create([
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'employee_contract_id' => $contract->id,
                    ]);
                }
            }

            DB::commit();

            session()->flash('success', 'Contract updated successfully.');
            $this->loadContract();
            $this->dispatch('contractUpdated');
            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error updating contract: ' . $e->getMessage());
        }
    }

    private function formatDateForInput(mixed $date): ?string
    {
        if (! $date) {
            return null;
        }

        return Carbon::parse($date)->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.edit-contract-model');
    }
}
