<div>
    <div class="modal fade" id="manageConflictModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $conflictId ? 'Edit Conflict' : 'Register Conflict' }}</h5>
                    @if ($reference_number)<small class="text-muted ms-2">{{ $reference_number }}</small>@endif
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="row g-3">
                            @include('livewire.partials.employee-search-field')
                            <div class="col-md-6 position-relative">
                                <label class="form-label fw-medium">Other Employee (optional)</label>
                                <input type="text" class="form-control" wire:model.live="other_employee_name"
                                    wire:click="searchOtherEmployee" wire:keyup="searchOtherEmployee" autocomplete="off"
                                    placeholder="Search other party...">
                                @if (!empty($otherEmployees))
                                    <div class="list-group position-absolute w-100 shadow-sm mt-1" style="z-index:1050;max-height:160px;overflow-y:auto;">
                                        @foreach ($otherEmployees as $emp)
                                            <button type="button" class="list-group-item list-group-item-action"
                                                wire:click="selectOtherEmployee({{ $emp->id }})">{{ $emp->full_name }}</button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" wire:model="conflict_date">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-medium">Subject <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="subject">
                                @error('subject')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Severity</label>
                                <select class="form-control" wire:model="severity">
                                    @foreach (\App\Enums\RelationSeverityEnum::cases() as $s)
                                        <option value="{{ $s->value }}">{{ $s->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Status</label>
                                <select class="form-control" wire:model="status">
                                    @foreach (\App\Enums\RelationCaseStatusEnum::cases() as $s)
                                        <option value="{{ $s->value }}">{{ $s->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="4" wire:model="description"></textarea>
                                @error('description')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-light" wire:click="closeModal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('livewire.partials.relation-modal-scripts')
