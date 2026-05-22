<?php

namespace App\Http\Utils\Traits;

use App\Models\ContractFile;
use App\Models\EmployeeContract;

trait AuthorizesContractAccess
{
    protected function authorizeOwnContract(EmployeeContract $contract): void
    {
        $employee = auth()->user()?->employee;

        if (! $employee || $contract->employee_id !== $employee->id) {
            abort(403, 'You are not authorized to access this contract.');
        }
    }

    protected function authorizeManageContract(EmployeeContract $contract): void
    {
        if (! auth()->user()?->can('view_contracts')) {
            abort(403, 'You are not authorized to manage contracts.');
        }
    }

    protected function authorizeContractFile(ContractFile $file, bool $ownOnly = false): void
    {
        $contract = $file->employeeContract;

        if (! $contract) {
            abort(404, 'Contract not found for this file.');
        }

        if ($ownOnly) {
            $this->authorizeOwnContract($contract);

            return;
        }

        $this->authorizeManageContract($contract);

        if (! auth()->user()?->can('download_contracts')) {
            abort(403, 'You are not authorized to download contract files.');
        }
    }
}
