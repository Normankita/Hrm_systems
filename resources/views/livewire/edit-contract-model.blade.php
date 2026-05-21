<div>
    <button type="button" class="btn btn-outline-primary mb-5"
     wire:click="openModal">
        <i class="bi bi-pencil-square me-1"></i> Edit Contract
    </button>

    <div class="modal fade" id="editContractModel" tabindex="-1" aria-labelledby="editContractModelLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content border-0 shadow">

                <div class="modal-header bg-light border-bottom">
                    <div>
                        <h5 class="modal-title mb-0" id="editContractModelLabel">Edit Contract</h5>
                        @if ($contract_number)
                            <small class="text-muted">{{ $contract_number }}</small>
                        @endif
                    </div>
                    <button type="button" class="btn-close" aria-label="Close" wire:click="closeModal"></button>
                </div>

                <div class="modal-body p-4">

                    <div wire:loading.flex wire:target="save,openModal"
                        class="align-items-center justify-content-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <span class="ms-2 text-muted">Please wait...</span>
                    </div>

                    <div wire:loading.remove wire:target="save,openModal">

                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row justify-content-center">
                            <div class="col-sm-12 col-md-10">
                                <form wire:submit.prevent="save">

                                    {{-- Employee --}}
                                    <div class="mb-4">
                                        <h6 class="text-uppercase text-muted fw-semibold small mb-3">
                                            <i class="bi bi-person-badge me-1"></i> Employee
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-6 position-relative">
                                                <label class="form-label fw-medium">Employee Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" wire:model.live="employee_name"
                                                    wire:click="searchEmployee" wire:keyup="searchEmployee" autocomplete="off"
                                                    placeholder="Search employee...">
                                                @error('employee_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                                @error('employee_id')
                                                    <small class="text-danger d-block">{{ $message }}</small>
                                                @enderror
                                                @if (!empty($employees))
                                                    <div class="list-group position-absolute w-100 shadow-sm mt-1"
                                                        style="z-index: 1050; max-height: 200px; overflow-y: auto;">
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
                                                    class="spinner-border spinner-border-sm text-primary mt-1"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-medium">Contract Number</label>
                                                <input type="text" class="form-control bg-light" value="{{ $contract_number }}"
                                                    readonly disabled>
                                            </div>
                                        </div>
                                    </div>
        
                                    <hr class="text-muted opacity-25">
        
                                    {{-- Contract details --}}
                                    <div class="mb-4">
                                        <h6 class="text-uppercase text-muted fw-semibold small mb-3">
                                            <i class="bi bi-file-earmark-text me-1"></i> Contract Details
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-medium">Contract Type <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" wire:model="contract_type">
                                                    <option value="">-- Select --</option>
                                                    @foreach (\App\Enums\ContractEnum::cases() as $type)
                                                        <option value="{{ $type->value }}">{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('contract_type')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-medium">Status <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" wire:model="contract_status">
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                    <option value="terminated">Terminated</option>
                                                    <option value="expired">Expired</option>
                                                </select>
                                                @error('contract_status')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-medium">Work Location</label>
                                                <input type="text" class="form-control" wire:model="work_location"
                                                    placeholder="e.g. Head Office">
                                                @error('work_location')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-medium">Termination Reason</label>
                                                <textarea class="form-control" rows="2" wire:model="termination_reason"
                                                    placeholder="Required if contract is terminated"></textarea>
                                                @error('termination_reason')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
        
                                    <hr class="text-muted opacity-25">
        
                                    {{-- Dates --}}
                                    <div class="mb-4">
                                        <h6 class="text-uppercase text-muted fw-semibold small mb-3">
                                            <i class="bi bi-calendar3 me-1"></i> Dates
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium">Start Date <span
                                                        class="text-danger">*</span></label>
                                                <input type="date" class="form-control" wire:model="start_date">
                                                @error('start_date')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium">End Date</label>
                                                <input type="date" class="form-control" wire:model="end_date">
                                                @error('end_date')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium">Probation End</label>
                                                <input type="date" class="form-control" wire:model="probation_end_date">
                                                @error('probation_end_date')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-medium">Signed Date</label>
                                                <input type="date" class="form-control" wire:model="signed_date">
                                                @error('signed_date')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
        
                                    <hr class="text-muted opacity-25">
        
                                    {{-- Compensation --}}
                                    <div class="mb-4">
                                        <h6 class="text-uppercase text-muted fw-semibold small mb-3">
                                            <i class="bi bi-cash-stack me-1"></i> Compensation
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-medium">Basic Salary</label>
                                                <input type="number" step="0.01" min="0" class="form-control"
                                                    wire:model="basic_salary" placeholder="0.00">
                                                @error('basic_salary')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-medium">Currency</label>
                                                <input type="text" class="form-control" wire:model="currency"
                                                    placeholder="TZS">
                                                @error('currency')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-medium">Payment Frequency</label>
                                                <select class="form-select" wire:model="payment_frequency">
                                                    <option value="">-- Select --</option>
                                                    <option value="Monthly">Monthly</option>
                                                    <option value="Weekly">Weekly</option>
                                                    <option value="Bi-Weekly">Bi-Weekly</option>
                                                    <option value="Annual">Annual</option>
                                                </select>
                                                @error('payment_frequency')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
        
                                    <hr class="text-muted opacity-25">
        
                                    {{-- Documents --}}
                                    <div class="mb-2">
                                        <h6 class="text-uppercase text-muted fw-semibold small mb-3">
                                            <i class="bi bi-folder2-open me-1"></i> Contract Documents
                                        </h6>
        
                                        @if (!empty($existingFiles))
                                            <p class="small text-muted mb-2">Current documents — click remove to delete on
                                                save:</p>
                                            <div class="row g-3 mb-4">
                                                @foreach ($existingFiles as $file)
                                                    @php
                                                        $markedForDelete = in_array($file['id'], $filesToDelete, true);
                                                    @endphp
                                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                                        <div
                                                            class="card h-100 border {{ $markedForDelete ? 'border-danger bg-danger bg-opacity-10' : 'border-light shadow-sm' }}">
                                                            <div class="card-body p-3 text-center position-relative">
                                                                <div
                                                                    class="rounded-3 bg-danger bg-opacity-10 d-inline-flex p-3 mb-2">
                                                                    <i
                                                                        class="bi bi-file-earmark-pdf text-danger fs-2"></i>
                                                                </div>
                                                                <p class="small mb-0 text-truncate fw-medium"
                                                                    title="{{ $file['original_name'] }}">
                                                                    {{ $file['original_name'] }}
                                                                </p>
                                                                <a href="{{ route('admin.contracts.download', $file['id']) }}"
                                                                    target="_blank"
                                                                    class="btn btn-link btn-sm text-decoration-none p-0 mt-1 {{ $markedForDelete ? 'disabled pe-none' : '' }}">
                                                                    <i class="bi bi-download"></i> Preview
                                                                </a>
                                                                <div class="position-absolute top-0 end-0 p-2">
                                                                    @if ($markedForDelete)
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-outline-secondary rounded-circle"
                                                                            wire:click="unmarkFileForDeletion({{ $file['id'] }})"
                                                                            title="Undo remove">
                                                                            <i class="bi bi-arrow-counterclockwise"></i>
                                                                        </button>
                                                                    @else
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-danger rounded-circle shadow-sm"
                                                                            wire:click="markFileForDeletion({{ $file['id'] }})"
                                                                            title="Remove document">
                                                                            <i class="bi bi-trash"></i>
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                                @if ($markedForDelete)
                                                                    <span
                                                                        class="badge bg-danger position-absolute bottom-0 start-50 translate-middle-x mb-2">Will
                                                                        be removed</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="small text-muted mb-3">No documents uploaded yet.</p>
                                        @endif
        
                                        <label class="form-label fw-medium">Add New Documents (PDF, max 10MB each)</label>
                                        <div class="border border-2 border-dashed rounded-3 p-4 bg-light text-center">
                                            <i class="bi bi-cloud-upload text-primary fs-2 d-block mb-2"></i>
                                            <input type="file" class="form-control" wire:model="files" multiple
                                                accept="application/pdf">
                                            <small class="text-muted d-block mt-2">You may upload multiple PDF files</small>
                                        </div>
                                        @error('files.*')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
        
                                        <div wire:loading wire:target="files" class="mt-2 small text-primary">
                                            <span class="spinner-border spinner-border-sm"></span> Uploading...
                                        </div>
        
                                        @if (!empty($files))
                                            <ul class="list-group list-group-flush mt-3 border rounded">
                                                @foreach ($files as $file)
                                                    <li
                                                        class="list-group-item d-flex align-items-center py-2 small">
                                                        <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                                                        {{ $file->getClientOriginalName() }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
        
                                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                        <button type="button" class="btn btn-light" wire:click="closeModal">Cancel</button>
                                        <button type="submit" class="btn btn-primary px-4" wire:loading.attr="disabled"
                                            wire:target="save">
                                            <span wire:loading.remove wire:target="save">
                                                <i class="bi bi-check-lg me-1"></i> Save Changes
                                            </span>
                                            <span wire:loading wire:target="save">Saving...</span>
                                        </button>
                                    </div>
                                </form>
                            </div
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
