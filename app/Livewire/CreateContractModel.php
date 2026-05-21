<?php

namespace App\Livewire;

use App\Http\Utils\Traits\EmployeeTrait;
use App\Models\ContractFile;
use App\Models\ContractType;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\EmployeeContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateContractModel extends Component
{
    use WithFileUploads;

    public bool $showModal = false;

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

    public array $files = [];

    private function fetchEmployeeSalary()
    {
        $employeeId = $this->employee_id;
        $employee = Employee::findOrFail($employeeId);
        $baseSalary = $employee->getBaseSalary();
        $this->basic_salary = $baseSalary ?? 0;
    }

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
            'files' => 'required|array|min:1',
            'files.*' => 'file|mimes:pdf|max:10240',
        ];
    }

    public function openModal(): void
    {
        $this->resetForm();
        $this->start_date = now()->format('Y-m-d');
        $this->showModal = true;
        $this->dispatch('show-modal', modalId: 'createContractModel');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->dispatch('hide-modal', modalId: 'createContractModel');
    }

    public function searchEmployee(): void
    {
        $employees = EmployeeTrait::getNonContractEmployees();

        if (strlen((string) $this->employee_name) < 1) {
            $this->employees = $employees->all();
            return;
        }

        $this->employees = $employees->filter(function ($employee) {
            return str_contains(strtolower($employee->full_name), strtolower($this->employee_name));
        })->values()->all();
    }

    public function selectEmployee(int $id): void
    {
        $employee = Employee::find($id);
        if ($employee) {
            $this->employee_id = $employee->id;
            $this->employee_name = $employee->full_name;
            $this->employees = [];
            $this->fetchEmployeeSalary();
        }
    }

    public function save(): void
    {
        $this->validate();

        $employee = Employee::find($this->employee_id);

        if (! $employee) {
            session()->flash('error', 'Selected employee not found.');
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

            $contract = EmployeeContract::create([
                'contract_type' => $this->contract_type,
                'contract_number' => EmployeeContract::next_contract_number(),
                'employee_id' => $this->employee_id,
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
                'created_by' => $authUser->id,
            ]);

            foreach ($this->files as $file) {
                $fileName = uniqid('contract_') . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('contracts', $fileName, 'local');

                ContractFile::create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'employee_contract_id' => $contract->id,
                ]);
            }

            DB::commit();

            session()->flash('success', 'Contract created successfully.');
            $this->dispatch('contractCreated');
            $this->closeModal();
            $this->resetForm();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error creating contract: ' . $e->getMessage());
        }
    }

    private function resetForm(): void
    {
        $this->reset([
            'employee_name', 'employee_id', 'employees', 'contract_type',
            'start_date', 'end_date', 'probation_end_date', 'signed_date',
            'work_location', 'basic_salary', 'currency', 'payment_frequency',
            'contract_status', 'termination_reason', 'files',
        ]);
        $this->currency = 'TZS';
        $this->contract_status = 'active';
    }

    public function render()
    {
        return view('livewire.create-contract-model');
    }
}
