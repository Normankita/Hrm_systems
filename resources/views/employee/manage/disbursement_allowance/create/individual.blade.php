@extends('layouts.system')

@section('content')
    <div class="row justify-contant-start" id="app">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-start">
                        <div class="col-md-12 mb-3">
                            <h4>Disburse Allowance to Individual Employees</h4>
                            <button v-on:click="disburseAllowance" v-if="true"
                                class="btn btn-primary btn-sm mt-2">disburse</button>
                            <!-- Button trigger modal -->
                        </div>
                    </div>
                    <table class="table dt-table">
                        <thead>
                            <tr>
                                <th>#
                                    <input type="checkbox" class="check-all">
                                </th>
                                <th>Employee Name</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                        <input type="checkbox" class="check-input" value="{{ $employee->id }}">
                                    </td>
                                    <td>{{ $employee->full_name }}</td>
                                    <td>{{ $employee->user?->activeRole()?->name ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <!-- Modal -->
            <div class="modal fade" id="disburseModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row justify-content-start">
                                <div class="col-md-6">
                                    <p>Choose Allowance</p>
                                    <ol>
                                        @foreach ($allowances as $allowance)
                                            <li>
                                                <input type="checkbox" v-on:change="selectAllowance({{ $allowance->id }})"
                                                    :value="{{ $allowance->id }}" class="p-1">
                                                {{ $allowance->name }}
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                                <div class="col-md-6">
                                    <p>Selected Employees</p>
                                    <ol>
                                        <li v-for="emp in modalEmployees" :key="emp.id">
                                            @{{ emp.full_name }}
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" v-on:click="confirmDisbursement" class="btn btn-primary">Save
                                changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let handler1 = new TableSelectionHandler('.dt-table', '.check-all');
        const app = Vue.createApp({
            data() {
                return {
                    optionEmployees: @json($employees),
                    modalEmployees: [],
                    groups: @json($groups),
                    // Define your data properties here
                    selectedEmployees: [],
                    selectedAllowance: [],
                };
            },
            mounted() {

            },
            methods: {
                // Define your methods here
                disburseAllowance() {
                    this.selectedEmployees = handler1.getSelected();
                    if (this.selectedEmployees.length < 1) {
                        alert("select atleast one employee from the list")
                        return;
                    };
                    this.modalEmployees = this.optionEmployees.filter(
                        emp => this.selectedEmployees.includes(emp.id.toString()));
                    $('#disburseModal').modal('show');
                },
                confirmDisbursement() {
                    let groupIds = this.groups.map(group => group.id);
                    NProgress.start();
                    const details = {
                        "allowanceIds": this.selectedAllowance,
                        "groupIds": groupIds,
                        "employeesIds": this.selectedEmployees,
                    };

                    const uri = "{{ route('disburse.allowance.individual.in.grouped') }}";
                    axios.post(uri, details)
                        .then(response => {
                            alert('Disbursement Completed Successfully...');
                        }).catch(error => {
                            alert('Error in disbursing allowances');
                        })
                        .finally(() => {
                            NProgress.done();
                            $('#disburseModal').modal('hide');
                        });

                },
                selectAllowance(allowanceId) {
                    if (!this.selectedAllowance.includes(allowanceId)) {
                        this.selectedAllowance.push(allowanceId);
                    }
                },
                deselectAllowance() {
                    const index = this.selectedAllowance.indexOf(allowanceId);
                    if (index > -1) {
                        this.selectedAllowance.splice(index, 1);
                    }
                },
            }
        }).mount('#app');
    </script>
@endsection
