<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <h5 class="mb-0">Resolutions</h5>
            @if ($allowManage && !$personalMode)
                @if ($requirePermission)
                    @can('edit_employee_relations')
                        <button type="button" class="btn btn-primary btn-sm" wire:click="$dispatch('openResolutionModal')">
                            <i class="mdi mdi-plus"></i> Add Resolution
                        </button>
                    @endcan
                @else
                    <button type="button" class="btn btn-primary btn-sm" wire:click="$dispatch('openResolutionModal')">
                        <i class="mdi mdi-plus"></i> Add Resolution
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
                        <th>Case</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Resolved</th>
                        <th>Docs</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($resolutions as $item)
                        <tr>
                            <td>{{ $item->reference_number }}</td>
                            <td>
                                @php $type = class_basename($item->resolvable_type); @endphp
                                <span class="badge bg-secondary">{{ $type }}</span>
                                @if ($item->resolvable)
                                    <small class="d-block text-muted">{{ $item->resolvable->reference_number ?? '' }}</small>
                                @endif
                            </td>
                            <td>{{ Str::limit($item->title, 40) }}</td>
                            <td><span class="badge bg-success">{{ $item->status }}</span></td>
                            <td>{{ $item->resolved_at?->format('d M Y') ?? '—' }}</td>
                            <td>
                                @if ($item->documents->count())
                                    <span class="badge bg-light text-dark border">{{ $item->documents->count() }} PDF</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if ($personalMode)
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        wire:click="$dispatch('viewResolution', { id: {{ $item->id }} })">View</button>
                                @elseif ($allowManage)
                                    @if ($requirePermission)
                                        @can('edit_employee_relations')
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                wire:click="$dispatch('editResolution', { id: {{ $item->id }} })">Edit</button>
                                        @elsecan('view_employee_relations')
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                wire:click="$dispatch('viewResolution', { id: {{ $item->id }} })">View</button>
                                        @endcan
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            wire:click="$dispatch('editResolution', { id: {{ $item->id }} })">Edit</button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">No resolutions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $resolutions->links('livewire::bootstrap') }}
    </div>
</div>
