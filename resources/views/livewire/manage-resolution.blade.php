<div>
    <div class="modal fade" id="manageResolutionModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $resolutionId ? 'Edit Resolution' : 'Record Resolution' }}</h5>
                    @if ($reference_number)<small class="text-muted ms-2">{{ $reference_number }}</small>@endif
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-medium">Related Case <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model.live="case_key" @if($resolutionId) disabled @endif>
                                    <option value="">-- Select open case --</option>
                                    @foreach ($openCases as $case)
                                        <option value="{{ $case['type'] }}|{{ $case['id'] }}">{{ $case['label'] }}</option>
                                    @endforeach
                                </select>
                                @error('resolvable_id')<small class="text-danger d-block">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="title">
                                @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Status</label>
                                <select class="form-control" wire:model="status">
                                    @foreach (\App\Enums\RelationCaseStatusEnum::cases() as $s)
                                        <option value="{{ $s->value }}">{{ $s->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Resolved Date</label>
                                <input type="date" class="form-control" wire:model="resolved_at">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">Summary <span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="3" wire:model="summary"></textarea>
                                @error('summary')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">Action Taken</label>
                                <textarea class="form-control" rows="3" wire:model="action_taken" placeholder="Steps taken to resolve..."></textarea>
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
