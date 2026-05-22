<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\AuthorizesContractAccess;
use App\Models\ContractFile;
use App\Models\EmployeeContract;
use Illuminate\Support\Facades\Storage;

class EmployeeContractsController extends Controller
{
    use AuthorizesContractAccess;

    public function index()
    {
        $employee = auth()->user()->employee;

        if (! $employee) {
            abort(403, 'Employee profile not found.');
        }

        return view('employee.contracts.index', compact('employee'));
    }

    public function show($id)
    {
        $contract = EmployeeContract::with([
            'employee.department',
            'employee.designation',
            'employee.company',
            'contractFiles',
            'createdBy',
        ])->findOrFail($id);

        $this->authorizeOwnContract($contract);

        return view('employee.contracts.show', compact('contract'));
    }

    public function download($id)
    {
        $file = ContractFile::with('employeeContract')->findOrFail($id);
        $this->authorizeContractFile($file, ownOnly: true);

        $path = storage_path('app/private/' . $file->file_path);

        if (! file_exists($path)) {
            abort(404, 'File not found');
        }

        return response()->download(
            $path,
            $file->original_name ?? 'contract.pdf'
        );
    }
}
