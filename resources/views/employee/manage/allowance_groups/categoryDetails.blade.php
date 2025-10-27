@extends('layouts.system')

@section('content')
    <div class="row justify-content-start" id="emps">
        <div class="col-md-12">
            <div class="row justify-content-start">
                <div class="col">
                    <button class="btn btn-sm btn-primary mx-1 mt-1" type="button" v-on:click="desburseAllowance()">
                        Disburse to selected
                    </button>

                    <a class="btn btn-sm btn-primary mx-1 mt-1"
                        href="{{ route('employee.manage.employee.allowances.groups.editMengers',
                            [$group->id, $allowance->id]) }}">
                            Add Employee to allowance
                    </a>
                </div>
            </div>
        </div>
        <div class="mt-4 mb-4">
            <h3 class="text-dark font-weight-bold"><b>{{ $group->name }}</b> Group Directory</h3>
            <p class="text-muted">
                <b>{{ $allowance->name }} User's</b>
            </p>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <x-system.table class="dt-table w-100 table-responsive">
                        <x-slot name="head">
                            <input type="checkbox" class="all-checker" id="select-all">
                            <b>Select All</b>
                            <thead class="table-light text-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Current Count</th>
                                    <th>Target Count</th>
                                    <th>Amount</th>
                                    <th>Department</th>
                                    <th>Join Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </x-slot>
                        <x-slot name="body">
                            <tbody>
                                @forelse($groupWithEmp as $key => $withEmployee)
                                    @php
                                        $employee = $withEmployee->employee;
                                        $textColor = $withEmployee->isEligible ? 'green' : 'red';
                                    @endphp
                                    <tr style="background-color: red !important;">
                                        <td style="color:{{ $textColor }}">{{ ++$key }}
                                            @if ($withEmployee->isEligible)
                                                <input type="checkbox" class="row-checker" name="employee_id"
                                                    value="{{ $withEmployee->pivotId }}">
                                            @endif
                                        </td>
                                        <td style="color:{{ $textColor }}">{{ $employee->full_name }}</td>
                                        <td style="color:{{ $textColor }}">{{ $employee->phone_number }}</td>
                                        <td style="color:{{ $textColor }}">{{ $withEmployee->count }}</td>
                                        <td style="color:{{ $textColor }}">
                                          {{ $withEmployee->frequency->no_times }}
                                        </td>
                                        <td style="color:{{ $textColor }}">
                                            {{ number_format($withEmployee->pivotAllowanceAmount) }}</td>
                                        <td style="color:{{ $textColor }}">{{ $employee->department->name ?? 'N/A' }}
                                        </td>
                                        <td style="color:{{ $textColor }}">
                                            {{ \Carbon\Carbon::parse($withEmployee->effective_from)->format('m-d-Y') }}

                                        </td>
                                        <td>
                                            {{-- <x-system.btn-view :key="$key" :route="route('employee.manage.employees.show', $employee->id)" /> --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">
                                            No Disbursements.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </x-slot>
                    </x-system.table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div>
                        <h5 class="text-dark font-weight-bold">
                            Disbursement History for Group-Category
                        </h5>
                    </div>
                    <x-system.table class="dt-table w-100 table-responsive">
                        <x-slot name="head">
                            <thead class="table-light text-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Phone</th>
                                    <th>Amount</th>
                                    <th>Disbursement Origin</th>
                                    <th>Disbursed On</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </x-slot>

                        <x-slot name="body">
                            <tbody>
                                @php $loopCount = 0; @endphp
                                @forelse($disbursed as $date => $items)
                                    @php $loopCount++; @endphp
                                    @foreach ($items as $key => $item)
                                        <tr class="text-dark">
                                            <td>{{ ($key + 1) * $loopCount }}</td>
                                            <td>{{ $item->employee->full_name }}</td>
                                            <td>{{ $item->employee->phone_number }}</td>
                                            <td>{{ number_format($item->amount) }}</td>
                                            <td>{{ $item->type }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('m-d-Y') }}</td>
                                            <td>
                                                {{-- <x-system.btn-view :key="$key" :route="route('employee.manage.employees.show', $employee->id)" /> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">No employees found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </x-slot>
                    </x-system.table>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2-multi-search').select2({
                placeholder: "Search and select employees",
                allowClear: true
            });
        });
    </script>


    <script>
        const handler1 = new TableSelectionHandler('.dt-table', '.all-checker');
        const app = Vue.createApp({
            data() {
                return {
                    group: {!! json_encode($group) !!},
                    allowance: {!! json_encode($allowance) !!},
                };
            },
            methods: {
                async desburseAllowance() {
                    if (confirm('Are you sure you want to proceed?')) {
                        const result = await axios.post(
                                "{{ route('disbursements.disburse') }}", {
                                    basedOn: 'category',
                                    group_allowance_employee_pivotIds: handler1.getSelected(),
                                    groupId: this.group.id,
                                    allowanceId: this.allowance.id
                                })
                            .then(resp => {
                                if (resp.status == 200) {
                                    if (resp.data) {
                                        if (resp.data.status == 'success') {
                                            showToast("Data saved successfully!", 'success', 4000);
                                            location.reload();
                                        } else {
                                            showToast(
                                                "Something went wrong. Please try again...",
                                                "error", 6000);
                                        }
                                    }
                                } else {
                                    showToast(
                                        "Fail to create, plase try again...",
                                        "error", 6000);
                                }
                            });
                    }
                }
            },
        }).mount('#emps');
    </script>
@endsection
