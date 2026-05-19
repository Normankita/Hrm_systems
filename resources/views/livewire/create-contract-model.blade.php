<div>
    <!-- Button to open modal -->
    <button type="button" class="btn btn-primary" wire:click="openModal">
        Create New Contract
    </button>

    <!-- Modal -->
    <div class="modal fade" id="createContractModel" tabindex="-1" 
    aria-labelledby="createContractModelLabel"
        aria-hidden="true" wire:ignore.self> <!-- Important -->

        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="createContractModelLabel">Create new Contract Form</h5>
                    <button type="button" class="close" aria-label="Close" wire:click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    {{-- Your form or content here --}}

                    <div class="card shadow-sm border-0">

                        <div class="card-header bg-white">
                            <h5 class="mb-0">Create Contract</h5>
                        </div>

                        {{-- LOADING SPINNER --}}
                        <div class="card-body" wire:loading wire:target="save">
                            <div class="d-flex align-items-center">
                                <strong>Loading...</strong>
                                <div class="spinner-border ms-auto" role="status" aria-hidden="true"></div>
                            </div>
                        </div>

                        {{-- FORM (Hidden while loading) --}}
                        <div class="card-body" wire:loading.remove wire:target="save">

                            @if (session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form wire:submit.prevent="save" enctype="multipart/form-data">

                                <div class="row g-3">

                                    {{-- Employee Name --}}
                                    <div class="col-md-4 position-relative">
                                        <label class="form-label">Employee Name</label>
                                        <input type="text" class="form-control" wire:model.live="employee_name"
                                            wire:click="searchEmployee" wire:keyup="searchEmployee" autocomplete="off">

                                        @error('employee_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror

                                        @if (!empty($employees))
                                            <div class="list-group position-absolute w-100 shadow-sm"
                                                style="z-index: 1000;">
                                                @foreach ($employees as $employee)
                                                    <button type="button"
                                                        class="list-group-item list-group-item-action"
                                                        wire:click="selectEmployee({{ $employee->id }})">
                                                        {{ $employee->full_name }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif
                                        <span wire:loading wire:target="searchEmployee"
                                            class="spinner-border spinner-border-sm"></span>
                                    </div>

                                    {{-- Contract Type --}}
                                    <div class="col-md-4">
                                        <label class="form-label">Contract Type</label>
                                        <select class="form-control" wire:model="contract_type">
                                            <option value="">-- Select Contract Type --</option>
                                            @foreach (\App\Enums\ContractEnum::cases() as $type)
                                                <option value="{{ $type->value }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('contract_type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Created At --}}
                                    <div class="col-md-4">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" class="form-control" wire:model="created_at">
                                        @error('created_at')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- File Upload --}}
                                    <div class="col-12">
                                        <label class="form-label">Upload Contracts (PDF files)</label>
                                        <input type="file" class="form-control" wire:model="files" multiple
                                            accept="application/pdf">

                                        @error('files.*')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror

                                        @if ($files)
                                            <div class="mt-2">
                                                <strong>Selected Files:</strong>
                                                <ul class="mb-0">
                                                    @foreach ($files as $file)
                                                        <li>{{ $file->getClientOriginalName() }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>

                                </div>

                                <div class="mt-4">
                                    <button class="btn btn-primary" type="submit">
                                        Save Contract
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('livewire:initialized', () => {

        Livewire.on('show-modal', ({
            modalId
        }) => {
            const modal = new bootstrap.Modal(document.getElementById(modalId));
            modal.show();
        });

        Livewire.on('hide-modal', ({
            modalId
        }) => {
            const modalEl = document.getElementById(modalId);
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        });
    });
</script>
