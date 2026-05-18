<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <h5 class="mb-0">Contracts</h5>
    </div>


    <div class="card-body">

        {{-- FILTERS --}}
        <div class="row g-3 mb-3">

            {{-- Employee Name --}}
            <div class="col-md-4">
                <input type="text"
                       class="form-control"
                       placeholder="Employee name..."
                       wire:model.debounce.500ms="employee_name">
            </div>

            {{-- Contract Type --}}
            <div class="col-md-3">
                <input type="text"
                       class="form-control"
                       placeholder="Contract type..."
                       wire:model.debounce.500ms="contract_type">
            </div>

            {{-- Created At (from date) --}}
            <div class="col-md-3">
                <input type="date"
                       class="form-control"
                       wire:model="created_at">
            </div>

            {{-- Reset --}}
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-secondary"
                        wire:click="resetFilters">
                    Reset
                </button>
            </div>

        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Contract #</th>
                        <th>Employee Name</th>
                        <th>Contract Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Created At</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($contracts as $contract)
                        <tr>
                            <td>{{ $contract->contract_number }}</td>
                            <td>{{ $contract->employee_name }}</td>
                            <td>{{ $contract->contract_type }}</td>
                            <td>{{ $contract->start_date }}</td>
                            <td>{{ $contract->end_date }}</td>
                            <td>{{ $contract->created_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No contracts found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>