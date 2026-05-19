<?php

namespace App\Livewire;

use App\Models\ContractFile;
use App\Models\ContractType;
use App\Models\Designation;
use App\Models\EmployeeContract;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateContractModel extends Component
{

    use WithFileUploads;

    public $employee_name;
    public $contract_type;
    public $created_at;
    public $employees = [];
    public $employee_id = null;
    public $work_location;
    public $basic_salary;
    public $files = []; // multiple uploads


    protected $rules = [
        'employee_name' => 'required|string|max:255',
        'contract_type' => 'required|string|max:255',
        'created_at'    => 'required|date',
        'files.*'       => 'file|mimes:pdf|max:10240', // 10MB each
    ];


    public $showModal = false;

    public function openModal()
    {
        $this->showModal = true;

        // Dispatch browser event to show Bootstrap modal
        $this->dispatch('show-modal', modalId: 'createContractModel');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->dispatch('hide-modal', modalId: 'createContractModel');
    }


    public function searchEmployee()
    {
        if (strlen($this->employee_name) < 1) {
            $this->employees = Employee::where('full_name', 'like', '%' . $this->employee_name . '%')
                ->orderBy('full_name')->limit(20)
             ->get();
            return;
        }
        $this->employees = Employee::where('full_name', 'like', '%' . $this->employee_name . '%')
            ->limit(100)
            ->get();
    }

    public function selectEmployee(int $id)
    {
        $employee = Employee::find($id);

        if ($employee) {
            $this->employee_id = $employee->id;
            $this->employee_name = $employee->full_name;
            $this->employees = [];
        }
    }

    public function save()
    {

        $this->validate();

        $employee = Employee::find($this->employee_id);
        if (!$employee) {
            session()->flash('error', 'Selected employee not found.');
            return;
        }

        DB::beginTransaction();
        try {
            $authUser = Auth::user();
            $contract_number = EmployeeContract::next_contract_number();
            $contract_id = ContractType::getOrCreateContractType($this->contract_type)->id;

            $designation_id = Designation::getOrCreateDesignation(
                $employee->role()->name,
                $employee->department_id)->id;


            // 1. Create contract
            $contract = EmployeeContract::create([
                'employee_name' => $this->employee_name,
                'contract_type' => $this->contract_type,
                'contract_number' => $contract_number,
                'created_at'    => $this->created_at,
                'employee_id'   => $this->employee_id,
                'contract_type_id' => $contract_id,
                'department_id' => $employee->department_id,
                'designation_id' => $designation_id,
                'start_date' => $this->created_at,
                'end_date' => null,
                'probation_end_date' => null,
                'created_by' => $authUser->id,
                'currancy' => 'TZS'
            ]);

            if ($this->files) {
                foreach ($this->files as $file) {

                    // secure filename (avoid original exposure)
                    $fileName = uniqid('contract_') . '.' . $file->getClientOriginalExtension();

                    // store in PRIVATE disk (NOT public)
                    $path = $file->storeAs('contracts', $fileName, 'local');

                    ContractFile::create([
                        'contract_id' => $contract->id,
                        'file_path'   => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'employee_contract_id' => $contract->id,
                    ]);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error creating contract: ' . $e->getMessage());
            return;
        }

        DB::commit();

        session()->flash('success', 'Contract created successfully with files.');

        $this->reset(['employee_name', 'contract_type', 'created_at', 'files']);

        $this->dispatch('contractCreated');

    }
    


    public function render()
    {
        return view('livewire.create-contract-model');
    }
}
