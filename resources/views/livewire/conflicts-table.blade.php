<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <h5 class="mb-0">Conflicts</h5>
            @if ($personalMode)
                <button type="button" class="btn btn-primary btn-sm" wire:click="$dispatch('openMyConflictModal')">
                    <i class="mdi mdi-plus"></i> Report Conflict
                </button>
            @elseif ($allowManage)
                @if ($requirePermission)
                    @can('edit_employee_relations')
                        <button type="button" class="btn btn-primary btn-sm" wire:click="$dispatch('openConflictModal')">
                            <i class="mdi mdi-plus"></i> Register Conflict
                        </button>
                    @endcan
                @else
                    <button type="button" class="btn btn-primary btn-sm" wire:click="$dispatch('openConflictModal')">
                        <i class="mdi mdi-plus"></i> Register Conflict
                    </button>
                @endif
            @endif
        </div>
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm" wire:model.live.debounce.300ms="search" placeholder="Search...">
            </div>
            <div class="col-md-4">
                <select class="form-control form-control-sm" wire:model.live="statusFilter">
                    <option value="">All statuses</option>
                    @foreach (\App\Enums\RelationCaseStatusEnum::cases() as $s)
                        <option value="{{ $s->value }}">{{ $s->value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Ref #</th>
                        @if (!$personalMode)
                            <th>Employee</th>
                        @endif
                        <th>Other Party</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Docs</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($conflicts as $item)
                        <tr>
                            <td>{{ $item->reference_number }}</td>
                            @if (!$personalMode)
                                <td>{{ $item->employee?->full_name }}</td>
                            @endif
                            <td>{{ $item->otherEmployee?->full_name ?? '—' }}</td>
                            <td>{{ Str::limit($item->subject, 35) }}</td>
                            <td>{{ $item->conflict_date->format('d M Y') }}</td>
                            <td><span class="badge bg-primary">{{ $item->status }}</span></td>
                            <td>
                                @if ($item->documents->count())
                                    <span class="badge bg-light text-dark border">{{ $item->documents->count() }} PDF</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-nowrap">
                                @if ($personalMode && $item->status === 'Open')
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        wire:click="$dispatch('editConflict', { id: {{ $item->id }} })">Edit</button>
                                @elseif ($personalMode)
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        wire:click="$dispatch('viewConflict', { id: {{ $item->id }} })">View</button>
                                @elseif ($allowManage)
                                    @if ($requirePermission)
                                        @can('edit_employee_relations')
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                wire:click="$dispatch('editConflict', { id: {{ $item->id }} })">Edit</button>
                                        @elsecan('view_employee_relations')
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                wire:click="$dispatch('viewConflict', { id: {{ $item->id }} })">View</button>
                                        @endcan
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            wire:click="$dispatch('editConflict', { id: {{ $item->id }} })">Edit</button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ $personalMode ? 7 : 8 }}" class="text-center text-muted py-3">No conflicts found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $conflicts->links('livewire::bootstrap') }}
    </div>
</div>
