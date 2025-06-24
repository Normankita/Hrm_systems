@php
    use App\Http\Resources\UserResource;

@endphp
@extends('layouts.system')
@section('content')
    <div class="row justify-content-center" id="emps">
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
                            <option v-bind:value="employee" v-for="(employee, index) in employees">
                                @{{ employee.full_name }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="amount">Amount</label>
                        <input type="text" v-model="amount" class="form-control" placeholder="amount">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-sm btn-primary" type="button" v-on:click="addEmployee">add</button>
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
            <button class="btn btn-sm btn-primary" type="button" v-on:click="submitChosenEmployee">submit</button>
        </div>
    </div>
@endsection
@php
    // extract user by resource
    $user = new UserResource(auth()->user());
@endphp

@section('scripts')
    <script>
        // Initialize the handler and store the instance
        const handler1 = new TableSelectionHandler('.dt-table', '.all-checker');

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
                deleteEmployeeFromGroup() {
                    let dt = handler1.getSelected();
                    const route = `/api/groups/remove/employees/from/group/${this.group.id}`;
                    axios.post(route, {employees: handler1.getSelected(), user: this.user})
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