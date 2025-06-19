@extends('layouts.system')

@section('content')
    @canany(['edit_allowances', 'view_allowances', 'create_allowances'])
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-start">
                        <div class="col-md-6">
                            <h3 class="card-title lead" style="text-transform: capitalize;">
                                {{ strtoupper($employee->full_name) }}
                            </h3>
                        </div>

                    </div>
                    {{-- Create Allowance --}}
                    @can('create_allowances')
                        <x-system.modal-button id="createAllowanceModal" form="createAllowanceForm" title="Create Allowance"
                            text="Give Allowance" />

                        <x-system.modal size="modal-lg" id="createAllowanceModal" form="createAllowanceForm"
                            title="Assign Allowance" :inside="true">
                            <form action="{{ route('employee.manage.employee.allowances.store', $employee->id) }}" method="POST"
                                id="createAllowanceForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="amount" class="form-label">Employee Name</label>
                                        <input type="readonly" readonly step="0.01" class="form-control" required
                                            value="{{ $employee->full_name }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="allowance_id" class="form-label">Allowance</label>
                                        <select name="allowance_id" class="form-control" required>
                                            <option disabled selected>Select Allowance</option>
                                            @foreach ($allowances as $allowance)
                                                <option value="{{ $allowance->id }}" @selected(old('allowance_id') == $allowance->id)>
                                                    {{ $allowance->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('allowance_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="number" name="amount" step="0.01" class="form-control" required
                                            value="{{ old('amount') }}">
                                        @error('amount')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="effective_from" class="form-label">Effective From</label>
                                        <input type="date" name="effective_from" class="form-control"
                                            value="{{ old('effective_from') }}">
                                        @error('effective_from')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="effective_to" class="form-label">Effective Till</label>
                                        <input type="date" name="effective_to" class="form-control"
                                            value="{{ old('effective_to') }}">
                                        @error('effective_to')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="frequency" class="form-label">Frequency</label>
                                        <select name="frequency" class="form-control" required>
                                            <option value="monthly" @selected(old('frequency') === 'monthly')>Monthly</option>
                                            <option value="quarterly" @selected(old('frequency') === 'quarterly')>Quarterly</option>
                                            <option value="yearly" @selected(old('frequency') === 'yearly')>Yearly</option>
                                            <option value="one-time" @selected(old('frequency') === 'one-time')>One-time</option>
                                        </select>
                                        @error('frequency')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary">Add Allowance</button>
                                    </div>
                                </div>
                            </form>
                        </x-system.modal>
                    @endcan

                    {{-- Allowances Table --}}
                    <div class="table-responsive mt-4">
                        <table class="table dt-table table-bordered">
                            <thead>
                                <tr>
                                    <th>Allowance</th>
                                    <th>Amount</th>
                                    <th>Frequency</th>
                                    <th>Effective From</th>
                                    <th>Effective To</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employee->allowances as $allowance)
                                    <tr>
                                        <td>{{ $allowance->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($allowance->pivot->amount, 2) }}</td>
                                        <td>{{ ucfirst($allowance->pivot->frequency) }}</td>
                                        <td>{{ $allowance->pivot->effective_from ?? 'N/A' }}</td>
                                        <td>{{ $allowance->pivot->effective_to ?? 'N/A' }}</td>
                                        <td>
                                            @can('edit_allowances')
                                                <form
                                                    action="{{ route('employee.manage.employee.allowances.toggleStatus', [$employee->id, $allowance->id]) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to {{ $allowance->pivot->status ? 'deactivate' : 'activate' }} this allowance?')">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status"
                                                        value="{{ $allowance->pivot->status ? 0 : 1 }}">
                                                    <button type="submit"
                                                        class="btn btn-sm {{ $allowance->pivot->status ? 'btn-outline-danger' : 'btn-outline-success' }} p-1">
                                                        {{ $allowance->pivot->status ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>
                                            @endcan

                                            @can('edit_allowances')
                                                <x-system.modal-button class="btn btn-outline-dark btn-sm p-1 m-1"
                                                    id="editAllowanceModal-{{ $allowance->id }}" text="Edit" textColor="" />
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Edit Allowance Modals --}}
                    @can('edit_allowances')
                        @foreach ($employee->allowances as $allowance)
                            <x-system.modal id="editAllowanceModal-{{ $allowance->id }}"
                                form="editAllowanceForm-{{ $allowance->id }}"
                                title="Edit Allowance - {{ $allowance->allowance->name ?? '' }}" size="md" :inside="true">
                                <form
                                    action="{{ route('employee.manage.employee.allowances.update', [$employee->id, $allowance->id]) }}"
                                    method="POST" id="editAllowanceForm-{{ $allowance->id }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="number" step="0.01" name="amount" class="form-control"
                                            value="{{ $allowance->amount }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="frequency" class="form-label">Frequency</label>
                                        <select name="frequency" class="form-control" required>
                                            <option value="monthly" @selected($allowance->frequency === 'monthly')>Monthly</option>
                                            <option value="quarterly" @selected($allowance->frequency === 'quarterly')>Quarterly</option>
                                            <option value="yearly" @selected($allowance->frequency === 'yearly')>Yearly</option>
                                            <option value="one-time" @selected($allowance->frequency === 'one-time')>One-time</option>
                                        </select>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">Update Allowance</button>
                                    </div>
                                </form>
                            </x-system.modal>
                        @endforeach
                    @endcan

                    {{-- Back Button --}}
                    <div class="mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary" style="border: 1px dashed">←
                            Back</a>
                    </div>
                </div>
            </div>
        </div>
    @endcanany
@endsection
