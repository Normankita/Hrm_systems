<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
    use App\Models\ContractFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AdminContractsController extends Controller
{
    public function index()
    {
        // Code to list contracts
        return view('admin.contracts.index');
    }



public function download($id)
{
    $file = ContractFile::findOrFail($id);

    // Spatie permission check
    if (!auth()->user()->can('view contracts')) {
        abort(403, 'Unauthorized');
    }

    // extra safety: prevent path traversal
    $path = storage_path('app/' . $file->file_path);

    if (!file_exists($path)) {
        abort(404, 'File not found');
    }

    return response()->download(
        $path,
        $file->original_name ?? 'contract.pdf'
    );
}
}
