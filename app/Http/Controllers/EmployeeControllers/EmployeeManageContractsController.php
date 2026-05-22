<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\AuthorizesContractAccess;
use App\Models\ContractFile;
use App\Models\EmployeeContract;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class EmployeeManageContractsController extends Controller
{
    use AuthorizesContractAccess, AuthorizesRequests;

    public function index()
    {
        $this->authorize('view_contracts');

        return view('employee.manage.contracts.index');
    }

    public function show($id)
    {
        $this->authorize('view_contracts');

        $contract = EmployeeContract::findOrFail($id);
        $this->authorizeManageContract($contract);

        return view('employee.manage.contracts.show', compact('contract'));
    }

    public function download($id)
    {
        $this->authorize('download_contracts');

        $file = ContractFile::with('employeeContract')->findOrFail($id);
        $this->authorizeContractFile($file, ownOnly: false);

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
