@extends('layouts.system')

@section('content')
    @canany(['edit_allowances', 'view_allowances', 'create_allowances'])
        <div class="row" id="app">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-start">
                        <div class="col-md-6">
                            <h3 class="card-title lead" style="text-transform: capitalize;">
                                {{ strtoupper($employee->full_name) }}
                            </h3>
                        </div>
                    </div>
                    <button class="btn btn-primary mx-2" v-on:click="disburseSelected" type="button">
                        Disburse Selected
                    </button>
                    {{-- Create Allowance --}}
                    @can('create_allowances')
                        <x-system.modal-button id="createAllowanceModal" form="createAllowanceForm" title="Create Allowance"
                            text="ALLOCATE ALLOWANCE" />

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
                                        <label for="frequency_id" class="form-label">Frequency</label>
                                        <select name="frequency_id" class="form-control" required>
                                            @foreach ($frequencies as $frequency)
                                                <option value="{{ $frequency->id }}" @selected(old('frequency_id') === '{{ $frequency->id }}')>
                                                    {{ $frequency->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('frequency_id')
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

                    <div class="row">
                        <div class="col-md-12">
                            {{-- Allowances Table --}}
                            <div class="table-responsive mt-4">
                                <table class="table dt-table table-bordered
                                 table-responsive">
                                    <input type="checkbox" id="all-checker" class="form-check" />
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Allowance</th>
                                            <th>Amount</th>
                                            <th>Frequency</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employee->employeeAllowances as $key => $ea)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" id="row-checker" class="form-check"
                                                        value="{{ $ea->id }}" />
                                                </td>
                                                <td>{{ $ea->allowance->name ?? 'N/A' }}</td>
                                                <td>{{ number_format($ea->amount, 2) }}</td>
                                                <td>{{ ucfirst($ea->frequency?->name ?? 'N/A') }}</td>
                                                <td>
                                                    @can('edit_allowances')
                                                        <form
                                                            action="{{ route('employee.manage.employee.allowances.toggleStatus', [$employee->id, $ea->allowance->id]) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to {{ $ea->status ? 'deactivate' : 'activate' }} this allowance?')">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="{{ $ea->status ? 0 : 1 }}">
                                                            <button type="submit"
                                                                class="btn btn-sm {{ $ea->status ? 'btn-outline-danger' : 'btn-outline-success' }} p-1">
                                                                {{ $ea->status ? 'Deactivate' : 'Activate' }}
                                                            </button>
                                                        </form>
                                                    @endcan

                                                    @can('edit_allowances')
                                                        <x-system.modal-button class="btn btn-outline-dark btn-sm p-1 m-1"
                                                            id="editAllowanceModal-{{ $ea->allowance->id }}" text="Edit"
                                                            textColor="" />
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12">
                            {{-- Pagination --}}
                            <div class="mt-3">
                                <h4>Disbursement History</h4>
                                <x-system.table class="dt-table table-responsive">
                                    <x-slot name="head">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Allowance</th>
                                                <th>From</th>
                                                <th>Amount</th>
                                                <th>Disbursed At</th>
                                            </tr>
                                        </thead>
                                    </x-slot>
                                    <x-slot name="body">
                                        <tbody>
                                            @foreach ($disbursements as $disbursement)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $disbursement->allowance->name ?? 'N/A' }}</td>
                                                    <td>
                                                        {{ $disbursement->categoryBased ? 'Group' : 'Individual' }}
                                                    </td>
                                                    <td>{{ number_format($disbursement->amount) }}</td>
                                                    <td>{{ $disbursement->created_at->format('Y-M-d H:i:s') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </x-slot>
                                </x-system.table>
                            </div>
                        </div>
                    </div>
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
                                        <input type="text" name="amount" class="form-control"
                                            value="{{ old('amount', $allowance->pivot->amount) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="frequency" class="form-label">Frequency</label>
                                        <select name="frequency_id" class="form-control" required>
                                            @foreach ($frequencies as $frequency)
                                                <option value="{{ $frequency->id }}" @selected(old('frequency_id', $allowance->pivot->allowance_frequency_id) == $frequency->id)>
                                                    {{ $frequency->name }}
                                                </option>
                                            @endforeach
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

@section('scripts')
    <script>
        const handler1 = new TableSelectionHandler('.dt-table', '#all-checker');
        const app = Vue.createApp({
            data() {
                return {

                }
            },
            methods: {
                disburseSelected() {
                    console.log('reach here')
                    const selectedAllowances = handler1.getSelected();
                    if (selectedAllowances.length === 0) {
                        alert('Please select at least one allowance to disburse.');
                        return;
                    }
                    // Handle the disbursement logic here
                    const uri = "{{ route('disbursements.disburse') }}";
                    const details = {
                        user: "{{ Auth::user()->id }}",
                        employee: "{{ $employee->id }}",
                        basedOn: "individual",
                        allowanceEmployeePivotIds: selectedAllowances
                    };
                    console.log("Uri", uri);
                    axios.post(`${uri}`, details)
                        .then(response => {
                            console.log('Disbursement successful:', response.data);
                            alert('Disbursement successful!');
                            // Optionally, you can refresh the page or update the UI
                            location.reload();
                        })
                }
            }
        });
        app.mount('#app');
    </script>
@endsection
