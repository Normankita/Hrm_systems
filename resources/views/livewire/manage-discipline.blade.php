<div>
    <div class="modal fade" id="manageDisciplineModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($viewOnly)
                            View Discipline
                        @elseif ($disciplineId)
                            Edit Discipline
                        @else
                            Record Discipline
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
                                    @include('livewire.partials.employee-search-field')
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" wire:model="discipline_date"
                                            @disabled($viewOnly)>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Action Type <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" wire:model="action_type"
                                            @disabled($viewOnly)>
                                            <option value="">-- Select --</option>
                                            @foreach (\App\Enums\DisciplineActionEnum::cases() as $a)
                                                <option value="{{ $a->value }}">{{ $a->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-medium">Status</label>
                                        <select class="form-control" wire:model="status" @disabled($viewOnly)>
                                            @foreach (\App\Enums\RelationCaseStatusEnum::cases() as $s)
                                                <option value="{{ $s->value }}">{{ $s->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-medium">Description <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="4" wire:model="description" @disabled($viewOnly)></textarea>
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
