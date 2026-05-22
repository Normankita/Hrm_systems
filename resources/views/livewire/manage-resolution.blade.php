<div>
    <div class="modal fade" id="manageResolutionModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($viewOnly)
                            View Resolution
                        @elseif ($resolutionId)
                            Edit Resolution
                        @else
                            Record Resolution
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
                            <form wire:submit.prevent="save">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-medium">Related Case <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" wire:model.live="case_key"
                                            @disabled($viewOnly || $resolutionId)>
                                            <option value="">-- Select open case --</option>
                                            @foreach ($openCases as $case)
                                                <option value="{{ $case['type'] }}|{{ $case['id'] }}">
                                                    {{ $case['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-medium">Title <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" wire:model="title"
                                            @disabled($viewOnly)>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Status</label>
                                        <select class="form-control" wire:model="status" @disabled($viewOnly)>
                                            @foreach (\App\Enums\RelationCaseStatusEnum::cases() as $s)
                                                <option value="{{ $s->value }}">{{ $s->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Resolved Date</label>
                                        <input type="date" class="form-control" wire:model="resolved_at"
                                            @disabled($viewOnly)>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-medium">Summary <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="3" wire:model="summary" @disabled($viewOnly)></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-medium">Action Taken</label>
                                        <textarea class="form-control" rows="3" wire:model="action_taken" @disabled($viewOnly)></textarea>
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
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                @else
                                    <div class="d-flex justify-content-end mt-4"><button type="button"
                                            class="btn btn-light" wire:click="closeModal">Close</button></div>
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
