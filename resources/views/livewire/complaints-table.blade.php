<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <h5 class="mb-0">Complaints</h5>
            <button type="button" class="btn btn-primary btn-sm" wire:click="$dispatch('openComplaintModal')">
                <i class="mdi mdi-plus"></i> Register Complaint
            </button>
        </div>
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm" wire:model.live.debounce.300ms="search"
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
                        <th>Employee</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($complaints as $item)
                        <tr>
                            <td>{{ $item->reference_number }}</td>
                            <td>{{ $item->employee?->full_name }}</td>
                            <td>{{ Str::limit($item->subject, 40) }}</td>
                            <td>{{ $item->complaint_date->format('d M Y') }}</td>
                            <td><span class="badge bg-warning text-dark">{{ $item->severity }}</span></td>
                            <td><span class="badge bg-primary">{{ $item->status }}</span></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                    wire:click="$dispatch('editComplaint', { id: {{ $item->id }} })">Edit</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">No complaints found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $complaints->links('livewire::bootstrap') }}
    </div>
</div>
