<div>
    <button type="button" class="btn btn-primary" wire:click="openModal">
        <i class="bi bi-plus-lg me-1"></i> Create New Contract
    </button>

    <div class="modal fade" id="createContractModel" tabindex="-1" aria-labelledby="createContractModelLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title mb-0" id="createContractModelLabel">Create Contract</h5>
                    <button type="button" class="btn-close" aria-label="Close" wire:click="closeModal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row justify-content-center">
                        <div class="col-sm-12 col-md-10">
                            <div wire:loading.flex wire:target="save,openModal"
                        class="align-items-center justify-content-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <span class="ms-2 text-muted">Please wait...</span>
                    </div>

                    <div wire:loading.remove wire:target="save,openModal">
                        @if (session()->has('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session()->has('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form wire:submit.prevent="save">
                            @include('livewire.partials.contract-form-fields')

                            <hr class="text-muted opacity-25">

                            <div class="mb-2">
                                <h6 class="text-uppercase text-muted fw-semibold small mb-3">
                                    <i class="bi bi-folder2-open me-1"></i> Contract Documents
                                </h6>
                                <label class="form-label fw-medium">Upload Documents (PDF, max 10MB each) <span class="text-danger">*</span></label>
                                <div class="border border-2 border-dashed rounded-3 p-4 bg-light text-center">
                                    <i class="bi bi-cloud-upload text-primary fs-2 d-block mb-2"></i>
                                    <input type="file" class="form-control" wire:model="files" multiple accept="application/pdf">
                                    <small class="text-muted d-block mt-2">At least one PDF is required</small>
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
                            </div>

                            <div class="d-flex justify-content-end gap-2 pt-3 border-top mt-4">
                                <button type="button" class="btn btn-light" wire:click="closeModal">Cancel</button>
                                <button type="submit" class="btn btn-primary px-4" 
                                       wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="save">
                                        <i class="bi bi-check-lg me-1"></i> Save Contract
                                    </span>
                                    <span wire:loading wire:target="save">Saving...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('show-modal', ({ modalId }) => {
            const el = document.getElementById(modalId);
            if (el) bootstrap.Modal.getOrCreateInstance(el).show();
        });
        Livewire.on('hide-modal', ({ modalId }) => {
            const el = document.getElementById(modalId);
            const modal = el ? bootstrap.Modal.getInstance(el) : null;
            if (modal) modal.hide();
        });
    });
</script>
