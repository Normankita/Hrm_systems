<?php

namespace App\Livewire\Traits;

use App\Models\EmployeeRelationDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

trait ManagesRelationDocuments
{
    use WithFileUploads;

    public array $files = [];

    /** @var array<int, array{id: int, original_name: string}> */
    public array $existingDocuments = [];

    /** @var array<int> */
    public array $documentsToDelete = [];

    protected function documentValidationRules(): array
    {
        return [
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:pdf|max:10240',
        ];
    }

    protected function loadRelationDocuments(Model $model): void
    {
        $model->loadMissing('documents');

        $this->existingDocuments = $model->documents
            ->map(fn (EmployeeRelationDocument $doc) => [
                'id' => $doc->id,
                'original_name' => $doc->original_name,
            ])
            ->values()
            ->all();

        $this->documentsToDelete = [];
        $this->files = [];
    }

    protected function resetRelationDocuments(): void
    {
        $this->existingDocuments = [];
        $this->documentsToDelete = [];
        $this->files = [];
    }

    public function markDocumentForDeletion(int $documentId): void
    {
        if (! in_array($documentId, $this->documentsToDelete, true)) {
            $this->documentsToDelete[] = $documentId;
        }
    }

    public function unmarkDocumentForDeletion(int $documentId): void
    {
        $this->documentsToDelete = array_values(
            array_filter($this->documentsToDelete, fn (int $id) => $id !== $documentId)
        );
    }

    protected function syncRelationDocuments(Model $model): void
    {
        foreach ($this->documentsToDelete as $documentId) {
            $document = EmployeeRelationDocument::where('documentable_type', $model::class)
                ->where('documentable_id', $model->id)
                ->where('id', $documentId)
                ->first();

            if ($document) {
                Storage::disk('local')->delete($document->file_path);
                $document->delete();
            }
        }

        if (empty($this->files)) {
            return;
        }

        foreach ($this->files as $file) {
            $fileName = uniqid('relation_') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('employee-relations', $fileName, 'local');

            EmployeeRelationDocument::create([
                'documentable_type' => $model::class,
                'documentable_id' => $model->id,
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'uploaded_by' => Auth::id(),
            ]);
        }
    }
}
