<?php

namespace App\Livewire;

use App\Models\EmployeeContract;
use Livewire\Attributes\On;
use Livewire\Component;

class ContractTable extends Component
{

    public $created_at;
    public $employee_name;
    public $contract_type;

    public function filter()
    {
        $contracts = EmployeeContract::query()
            ->select(
                'employee_contracts.*',
                'employees.FULL_NAME as employee_name',
                'contract_types.name as contract_type_name'
            )

            ->leftJoin('employees', 'employees.id', '=', 'employee_contracts.employee_id')
            ->leftJoin(
                'contract_types',
                'contract_types.id',
                '=',
                'employee_contracts.contract_type_id'
            )

            ->when($this->created_at, function ($query) {
                $query->whereDate(
                    'employee_contracts.created_at',
                    $this->created_at
                );
            })

            ->when($this->employee_name, function ($query) {
                $query->where(function ($q) {
                    $q->where(
                        'employees.first_name',
                        'LIKE',
                        '%' . $this->employee_name . '%'
                    )
                    ->orWhere(
                        'employees.last_name',
                        'LIKE',
                        '%' . $this->employee_name . '%'
                    );
                });
            })

            ->when($this->contract_type, function ($query) {
                $query->where(
                    'contract_types.name',
                    'LIKE',
                    '%' . $this->contract_type . '%'
                );
            })

            ->orderBy('employee_contracts.created_at', 'DESC');

        return $contracts;
    }


    /**
     * Function to fetch the contracts form the model
     * and pass them to render method
     */
    public function getContracts()
    {
        $contracts = $this->filter()
            ->get();

        return $contracts;
    }

    public function render()
    {
        $contracts = $this->getContracts();
        return view('livewire.contract-table', compact('contracts'));
    }

    // litsening to dispatch in the create contract component
    #[On('contractCreated')]
    public function refreshContracts()
    {
        // reload the table by re-rendering the component
        $this->render();
    }
}
