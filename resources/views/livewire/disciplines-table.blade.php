<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <h5 class="mb-0">Disciplinary Actions</h5>
            @if ($allowManage && !$personalMode)
                @if ($requirePermission)
                    @can('edit_employee_relations')
                        <button type="button" class="btn btn-primary btn-sm" wire:click="$dispatch('openDisciplineModal')">
                            <i class="mdi mdi-plus"></i> Record Discipline
                        </button>
                    @endcan
                @else
                    <button type="button" class="btn btn-primary btn-sm" wire:click="$dispatch('openDisciplineModal')">
                        <i class="mdi mdi-plus"></i> Record Discipline
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
                        <th>Action</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Docs</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($disciplines as $item)
                        <tr>
                            <td>{{ $item->reference_number }}</td>
                            @if (!$personalMode)
                                <td>{{ $item->employee?->full_name }}</td>
                            @endif
                            <td>{{ $item->action_type }}</td>
                            <td>{{ $item->discipline_date->format('d M Y') }}</td>
                            <td><span class="badge bg-primary">{{ $item->status }}</span></td>
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
                                        wire:click="$dispatch('viewDiscipline', { id: {{ $item->id }} })">View</button>
                                @elseif ($allowManage)
                                    @if ($requirePermission)
                                        @can('edit_employee_relations')
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                wire:click="$dispatch('editDiscipline', { id: {{ $item->id }} })">Edit</button>
                                        @elsecan('view_employee_relations')
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                wire:click="$dispatch('viewDiscipline', { id: {{ $item->id }} })">View</button>
                                        @endcan
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            wire:click="$dispatch('editDiscipline', { id: {{ $item->id }} })">Edit</button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ $personalMode ? 6 : 7 }}" class="text-center text-muted py-3">No discipline records.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $disciplines->links('livewire::bootstrap') }}
    </div>
</div>
