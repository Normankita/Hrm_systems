<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <h5 class="mb-0">Contracts</h5>
    </div>


    <div class="card-body">

        {{-- FILTERS --}}
        <div class="row g-3 mb-3">

            {{-- Employee Name --}}
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Employee name..." wire:model="nameSearch"
                    wire:input="filterBy('employee_name')">

            </div>

            {{-- Contract Type --}}
            <div class="col-md-3">
                  <select class="form-control"
                  wire:model="contractTypeSearch"
                  wire:input="filterBy('contract_type')">
                        <option value="">-- Contract Type --</option>
                        @foreach (\App\Enums\ContractEnum::cases() as $type)
                            <option value="{{ $type->value }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
            </div>

            {{-- Created At (from date) --}}
            <div class="col-md-3">
                <input type="date" class="form-control"
                wire:model="createdAtSearch" wire:input="filterBy('created_at')" placeholder="Created At">
            </div>

            {{-- Reset --}}
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-secondary" wire:click="resetFilters">
                    Reset
                </button>
            </div>

        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No:_</th>
                        <th>Contract #</th>
                        <th>Employee Name</th>
                        <th>Contract Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($contracts as $contract)
                        <tr wire:loading.remove wire:target="filterBy">
                            <!-- display a number with consideration to the link -->
                            <td>{{ $contracts->firstItem() + $loop->index }}</td>
                            <td>{{ $contract->contract_number }}</td>
                            <td>{{ $contract->employee_name }}</td>
                            <td>{{ $contract->contract_type }}</td>
                            <td>{{ $contract->start_date }}</td>
                            <td>{{ $contract->end_date }}</td>
                            <td>{{ $contract->created_at }}</td>
                            <td>
                                <a href="{{  route('admin.contracts.show', $contract->id) }}">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr wire:loading.remove wire:target="filterBy">
                            <td colspan="8" class="text-center text-muted py-4">
                                No contracts found
                            </td>
                        </tr>
                    @endforelse

                    <!-- Loading Row -->
                    <tr wire:loading wire:target="filterBy">
                        <td colspan="8" class="text-center py-4">
                            <span class="spinner-border text-primary" role="status"></span>
                            <span class="ms-2">Loading contracts...</span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- display a table links -->
            <div class="mt-3">
                {{ $contracts->links('livewire::bootstrap') }}
            </div>
        </div>

    </div>
</div>
