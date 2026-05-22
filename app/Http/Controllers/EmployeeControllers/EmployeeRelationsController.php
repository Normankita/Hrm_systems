<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\AuthorizesRelationAccess;
use App\Models\EmployeeRelationDocument;
class EmployeeRelationsController extends Controller
{
    use AuthorizesRelationAccess;

    public function index()
    {
        $employee = auth()->user()->employee;

        if (! $employee) {
            abort(403, 'Employee profile not found.');
        }

        return view('employee.employee-relations.index', compact('employee'));
    }

    public function download($id)
    {
        $document = EmployeeRelationDocument::findOrFail($id);
        $this->authorizeRelationDocument($document, ownOnly: true);

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
