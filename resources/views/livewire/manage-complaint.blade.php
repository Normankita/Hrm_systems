<div>
    <div class="modal fade" id="manageComplaintModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($viewOnly)
                            View Complaint
                        @elseif ($complaintId)
                            Edit Complaint
                        @else
                            Register Complaint
                        @endif
                    </h5>
                    @if ($reference_number)
                        <small class="text-muted ms-2">{{ $reference_number }}</small>
                    @endif
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-sm-12 col-md-10">
                            @if (session()->has('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            <form wire:submit.prevent="save">
                                <div class="row g-3">
                                    @if (!$lockEmployee)
                                        @include('livewire.partials.employee-search-field')
                                    @else
                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Employee</label>
                                            <input type="text" class="form-control bg-light"
                                                value="{{ $employee_name }}" readonly>
                                        </div>
                                    @endif
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" wire:model="complaint_date"
                                            @disabled($viewOnly)>
                                        @error('complaint_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label fw-medium">Subject <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" wire:model="subject"
                                            @disabled($viewOnly)>
                                        @error('subject')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-medium">Severity</label>
                                        <select class="form-control" wire:model="severity" @disabled($viewOnly)>
                                            @foreach (\App\Enums\RelationSeverityEnum::cases() as $s)
                                                <option value="{{ $s->value }}">{{ $s->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if (!$lockEmployee)
                                        <div class="col-md-4">
                                            <label class="form-label fw-medium">Status</label>
                                            <select class="form-control" wire:model="status"
                                                @disabled($viewOnly)>
                                                @foreach (\App\Enums\RelationCaseStatusEnum::cases() as $s)
                                                    <option value="{{ $s->value }}">{{ $s->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="col-12">
                                        <label class="form-label fw-medium">Description <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="4" wire:model="description" @disabled($viewOnly)></textarea>
                                        @error('description')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    @include('livewire.partials.relation-document-upload', [
                                        'downloadRoute' => $downloadRoute,
                                        'viewOnly' => $viewOnly,
                                    ])
                                </div>
                                @if (!$viewOnly)
                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <button type="button" class="btn btn-light"
                                            wire:click="closeModal">Cancel</button>
                                        <button type="submit" class="btn btn-primary"
                                            wire:loading.attr="disabled">Save</button>
                                    </div>
                                @else
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="button" class="btn btn-light"
                                            wire:click="closeModal">Close</button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('livewire.partials.relation-modal-scripts')
