<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\AuthorizesRelationAccess;
use App\Models\EmployeeRelationDocument;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class EmployeeManageRelationsController extends Controller
{
    use AuthorizesRelationAccess, AuthorizesRequests;

    public function index()
    {
        $this->authorize('view_employee_relations');

        return view('employee.manage.employee-relations.index');
    }

    public function download($id)
    {
        $this->authorize('download_employee_relations');

        $document = EmployeeRelationDocument::findOrFail($id);
        $this->authorizeRelationDocument($document, ownOnly: false);

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
