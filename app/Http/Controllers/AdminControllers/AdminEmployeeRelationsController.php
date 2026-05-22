<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\EmployeeRelationDocument;

class AdminEmployeeRelationsController extends Controller
{
    public function index()
    {
        return view('admin.employee-relations.index');
    }

    public function download($id)
    {
        $document = EmployeeRelationDocument::findOrFail($id);

        $path = storage_path('app/private/' . $document->file_path);

        if (! file_exists($path)) {
            abort(404, 'File not found');
        }

        return response()->download(
            $path,
            $document->original_name ?? 'document.pdf'
        );
    }
}
