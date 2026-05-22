<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
    use App\Models\ContractFile;
use App\Models\EmployeeContract;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AdminContractsController extends Controller
{
    public function index()
    {
        // Code to list contracts
        return view('admin.contracts.index');
    }

    public function show($id)
    {
        // fetch the contract
        $contract = EmployeeContract::findOrFail($id);

        return view('admin.contracts.show', compact('contract'));
    }

    public function download($id)
    {
        $file = ContractFile::findOrFail($id);

        // extra safety: prevent path traversal
        $path = storage_path('app/private/' . $file->file_path);

        if (!file_exists($path)) {
            abort(404, 'File not found');
        }

        return response()->download(
            $path,
            $file->original_name ?? 'contract.pdf'
        );
    }

}
