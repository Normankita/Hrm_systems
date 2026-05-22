<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <h5 class="mb-0">Complaints</h5>
            @if ($personalMode)
                <button type="button" class="btn btn-primary btn-sm" 
                    wire:click="$dispatch('openMyComplaintModal')">
                        <i class="mdi mdi-plus"></i> File Complaint
                </button>
            @elseif ($allowManage)
                @if ($requirePermission)
                    @can('edit_employee_relations')
                        <button type="button" class="btn btn-primary btn-sm" 
                            wire:click="$dispatch('openComplaintModal')">
                                <i class="mdi mdi-plus"></i> Register Complaint
                        </button>
                    @endcan
                @else
                    <button type="button" class="btn btn-primary btn-sm" 
                        wire:click="$dispatch('openComplaintModal')">
                            <i class="mdi mdi-plus"></i> Register Complaint
                    </button>
                @endif
            @endif
        </div>
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm" 
                    wire:model.live.debounce.300ms="search"
                        placeholder="Search reference, subject, employee...">
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
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th>Docs</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($complaints as $item)
                        <tr>
                            <td>{{ $item->reference_number }}</td>
                            @if (!$personalMode)
                                <td>{{ $item->employee?->full_name }}</td>
                            @endif
                            <td>{{ Str::limit($item->subject, 40) }}</td>
                            <td>{{ $item->complaint_date->format('d M Y') }}</td>
                            <td><span class="badge bg-warning text-dark">{{ $item->severity }}</span></td>
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
                                        wire:click="$dispatch('editComplaint', { id: {{ $item->id }} })">Edit</button>
                                @elseif ($personalMode)
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        wire:click="$dispatch('viewComplaint', { id: {{ $item->id }} })">View</button>
                                @elseif ($allowManage)
                                    @if ($requirePermission)
                                        @can('edit_employee_relations')
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                wire:click="$dispatch('editComplaint', { id: {{ $item->id }} })">Edit</button>
                                        @elsecan('view_employee_relations')
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                wire:click="$dispatch('viewComplaint', { id: {{ $item->id }} })">View</button>
                                        @endcan
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            wire:click="$dispatch('editComplaint', { id: {{ $item->id }} })">Edit</button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $personalMode ? 7 : 8 }}" class="text-center text-muted py-3">No complaints found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $complaints->links('livewire::bootstrap') }}
    </div>
</div>
