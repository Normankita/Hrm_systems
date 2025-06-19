@php
    use App\Http\Resources\UserResource;

@endphp

@extends('layouts.system')

@section('content')
    <div class="row justify-content-start" id="emps">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-md-4">
                            <div class="d-flex">
                                <x-system.modal-button text="Add Members" id="addMembers" />
                                <x-system.modal size="modal-xl" id="addMembers" title="Add Members">
                                    <div class="row justify-content-center">
                                        <div class="row justify-content-center" v-if="empSubmit">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <div v-if="!empSubmit" class="col-md-10 mb-5">
                                            <form action="#">
                                                <div class="row justify-content-start align-items-end">
                                                    <div class="col-md-5">
                                                        <label for="name">Choose Employee</label>
                                                        <select v-model="chosen" class="form-control">
                                                            <option value="">Choose Employee</option>
                                                            <option v-bind:value="employee"
                                                                v-for="(employee, index) in employees">
                                                                @{{ employee.full_name }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="amount">Amount</label>
                                                        <input type="text" v-model="amount" class="form-control"
                                                            placeholder="amount">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button class="btn btn-sm btn-primary" type="button"
                                                            v-on:click="addEmployee">add</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div v-show="selectedEmployees.length > 0" class="col-md-10">
                                            <div class="mb-3">
                                                <h4>SELECTED MEMBERS</h4>
                                            </div>
                                            <table class="table table-sm table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(employee, index) in selectedEmployees">
                                                        <td>
                                                            @{{ employee.full_name }}
                                                        </td>
                                                        <td>
                                                            @{{ employee.amount }}
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-danger" type="button"
                                                                v-on:click="removeEmployeeFromSelected(index)">
                                                                remove</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-10 mt-2">
                                            <button class="btn btn-sm btn-primary" type="button"
                                                v-on:click="submitChosenEmployee">submit</button>
                                        </div>
                                    </div>
                                </x-system.modal>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h2>Allowance Group</h2>
                                <h3 class="card-title lead" style="text-transform: capitalize;">
                                    {{ $group->name }}
                                </h3>
                            </div>
                            <div>
                                <h2>Group Description</h2>
                                <h3 class="card-title lead" style="text-transform: capitalize;">
                                    {{ $group->description }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-start mt-5">
                        <div class="col-md-12">
                            <div class="mb-5">
                                <h2>Group Members Table</h2>
                            </div>
                            <x-system.table class="dt-table">
                                <x-slot name="head">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <th>Role</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </x-slot>
                                <x-slot name="body">
                                    <tbody>
                                        <form action="/delete">
                                        @foreach ($group->employees as $key => $employee)
                                            <tr>
                                                <th>{{ $key + 1 }}
                                                    <input type="checkbox" name="employee" value="{{ $employee->id }}">
                                                </th>
                                                <th>{{ $employee->full_name }}</th>
                                                <th>{{ $employee->pivot->amount }}</th>
                                                <th>{{ $employee->user->activeRoles()?->name ?? 'N/A' }}</th>
                                                <th>
                                                    <x-system.btn-edit text="edit" route="#" />
                                                    <x-system.btn-delete text="remove" route="#" />
                                                </th>
                                            </tr>
                                        @endforeach
                                        </form>
                                    </tbody>
                                </x-slot>
                            </x-system.table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@php
    // extract user by resource
    $user = new UserResource(auth()->user());
@endphp

@section('scripts')
    <script>
        const employees = {!! json_encode($employees) !!};
        const allowanceGroup = {!! json_encode($group) !!};
        const user = {!! json_encode($user) !!};

        const app = Vue.createApp({
            data() {
                return {
                    employees: employees,
                    selectedEmployees: [],
                    chosen: '',
                    empSubmit: false,
                    groupMambers: [],
                    group: allowanceGroup,
                    error: null,
                    user: user,
                    amount: 0,

                    removedIndexes: [],
                };
            },
            methods: {
                addEmployee() {
                    if (this.chosen == '') {
                        return;
                    }
                    this.chosen.amount = this.amount;
                    this.selectedEmployees.push(this.chosen);
                    this.employees.splice(this.employees.indexOf(this.chosen), 1);
                    console.log(this.selectedEmployees);
                },
                removeEmployeeFromSelected(index) {
                    this.employees.push(this.selectedEmployees[index]);
                    this.selectedEmployees.splice(index, 1);
                },
                submitChosenEmployee() {
                    this.empSubmit = true;
                    let embeded = {
                        user: this.user,
                        employees: this.selectedEmployees
                    };
                    const route = `/api/groups/add/employees/to/group/${this.group.id}`;
                    axios.post(route, embeded)
                        .then(response => {
                            const data = response.data;
                            if (data.status == 'success') {
                                location.reload();
                            }
                        })
                        .catch(error => {
                            this.empSubmit = false;
                            this.error = "Something went wrong, refresh page and try again";
                        });

                },
            },

        }).mount('#emps');
    </script>
@endsection
