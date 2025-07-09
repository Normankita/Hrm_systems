@php
    use App\Models\AllowanceGroupEmployeePivot;
@endphp

@extends('layouts.system')

@section('content')
    <div class="row justify-content-start" id="emps">
        <div class="col-md-12">
            <button class="btn btn-sm btn-primary" type="button" v-on:click="desburseAllowance()">
                Disburse to selected
            </button>
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
                    <x-system.table class="dt-table w-100">
                        <x-slot name="head">
                            <input type="checkbox" class="all-checker" id="select-all">
                            <b>Select All</b>
                            <thead class="table-light text-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Amount</th>
                                    <th>Department</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </x-slot>

                        <x-slot name="body">
                            <tbody>
                                @forelse(AllowanceGroupEmployeePivot::withEmployees(
                                                $gr_employeePivot) as $key => $withEmployee)
                                    @php
                                        $employee = $withEmployee->employee;
                                    @endphp
                                    <tr class="text-dark">
                                        <td>{{ ++$key }}
                                            <input type="checkbox" class="row-checker" name="employee_id"
                                                value="{{ $withEmployee->pivotId }}">
                                        </td>
                                        <td>{{ $employee->full_name }}</td>
                                        <td>{{ $employee->phone_number }}</td>
                                        <td>{{ number_format($withEmployee->pivotAllowanceAmount) }}</td>
                                        <td>{{ $employee->department->name ?? 'N/A' }}</td>
                                        <td>
                                            {{-- <x-system.btn-view :key="$key" :route="route('employee.manage.employees.show', $employee->id)" /> --}}
                                        </td>
                                    </tr>
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

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div>
                        <h5 class="text-dark font-weight-bold">
                            Disbursement History for Group-Category
                        </h5>
                    </div>
                    <x-system.table class="dt-table w-100">
                        <x-slot name="head">
                            <thead class="table-light text-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Amount</th>
                                    <th>Disbursed On</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </x-slot>

                        <x-slot name="body">
                            <tbody>
                                @forelse($disbursed as $date => $items)
                                    @foreach ($items as $key => $item)
                                        <tr class="text-dark">
                                            <td>{{ $item->employee->full_name }}</td>
                                            <td>{{ $item->employee->phone_number }}</td>
                                            <td>{{ number_format($item->amount) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('m-d-Y'); }}</td>
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
                    console.log(handler1.getSelected());
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
                                    alert("Data saved successfully!");
                                    location.reload();
                                 }else {
                                   alert("Something went wrong. Please try again...");
                                 }
                                }
                            } else {
                             alert('Fail to create, plase try again...')
                            }
                        })
                }
            },
        }).mount('#emps');
    </script>
@endsection
