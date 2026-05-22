<div class="col-12">
    <label class="form-label fw-medium">
        Supporting Documents (PDF)
        @if ($required ?? false)
            <span class="text-danger">*</span>
        @endif
    </label>

    @if (!$viewOnly && !empty($existingDocuments))
        <p class="small text-muted mb-2">Current documents — click remove to delete on save:</p>
        <div class="row g-2 mb-3">
            @foreach ($existingDocuments as $doc)
                @php $marked = in_array($doc['id'], $documentsToDelete, true); @endphp
                <div class="col-sm-6 col-md-4">
                    <div class="card border h-100 {{ $marked ? 'border-danger bg-danger bg-opacity-10' : 'shadow-sm' }}">
                        <div class="card-body p-3 text-center position-relative">
                            <div class="rounded-3 bg-danger bg-opacity-10 d-inline-flex p-2 mb-2">
                                <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                            </div>
                            <p class="small mb-0 text-truncate fw-medium" title="{{ $doc['original_name'] }}">
                                {{ $doc['original_name'] }}
                            </p>
                            @if ($downloadRoute ?? false)
                                <a href="{{ route($downloadRoute, $doc['id']) }}" target="_blank"
                                    class="btn btn-link btn-sm p-0 mt-1 {{ $marked ? 'disabled pe-none' : '' }}">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            @endif
                            @if (!$viewOnly)
                                <div class="position-absolute top-0 end-0 p-1">
                                    @if ($marked)
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle"
                                            wire:click="unmarkDocumentForDeletion({{ $doc['id'] }})" title="Undo">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-danger rounded-circle"
                                            wire:click="markDocumentForDeletion({{ $doc['id'] }})" title="Remove">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @elseif ($viewOnly && !empty($existingDocuments))
        <div class="row g-2 mb-3">
            @foreach ($existingDocuments as $doc)
                <div class="col-sm-6 col-md-4">
                    <div class="card border shadow-sm h-100">
                        <div class="card-body p-3 text-center">
                            <i class="bi bi-file-earmark-pdf text-danger fs-3"></i>
                            <p class="small mb-1 text-truncate">{{ $doc['original_name'] }}</p>
                            @if ($downloadRoute ?? false)
                                <a href="{{ route($downloadRoute, $doc['id']) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if (!$viewOnly)
        <div class="relation-dropzone border border-2 border-dashed rounded-3 p-4 text-center bg-light position-relative"
            ondragover="event.preventDefault(); this.classList.add('border-primary', 'bg-primary', 'bg-opacity-10')"
            ondragleave="this.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10')"
            ondrop="event.preventDefault(); this.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10')">
            <i class="bi bi-cloud-arrow-up text-primary display-6 d-block mb-2"></i>
            <p class="mb-2 fw-medium">Drag & drop PDF files here</p>
            <p class="small text-muted mb-3">or click to browse (max 10MB each)</p>
            <input type="file" class="form-control" wire:model="files" multiple accept="application/pdf">
        </div>
        @error('files')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
        @error('files.*')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror

        <div wire:loading wire:target="files" class="mt-2 small text-primary">
            <span class="spinner-border spinner-border-sm"></span> Uploading...
        </div>

        @if (!empty($files))
            <ul class="list-group list-group-flush mt-3 border rounded">
                @foreach ($files as $file)
                    <li class="list-group-item d-flex align-items-center py-2 small">
                        <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                        {{ $file->getClientOriginalName() }}
                    </li>
                @endforeach
            </ul>
        @endif
    @endif
</div>

<style>
    .relation-dropzone {
        transition: border-color 0.2s, background-color 0.2s;
    }
</style>
