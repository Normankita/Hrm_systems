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
                                <a href="{{ route('employee.manage.employee.allowances.groups.members', $group) }}" class="btn btn-primary"> Add Members </a>
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
                            <div>
                            </div>
                            <x-system.table class="dt-table">
                                <x-slot name="head">
                                    <label for="all">select all</label>
                                    <input class="all-checker m-2" type="checkbox" name="all">
                                    <thead>
                                        <tr>
                                            <th>#
                                            </th>
                                            <th>Name</th>
                                            <th>Role</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </x-slot>
                                <x-slot name="body">
                                    <tbody>
                                        @foreach ($group->activeEmployees as $key => $employee)
                                            <tr>
                                                <th>{{ $key + 1 }}
                                                    <input class="row-checker" data-check="column" type="checkbox"
                                                        name="employee" value="{{ $employee->id }}">
                                                </th>
                                                <th>{{ $employee->full_name }}</th>
                                                <th>{{ $employee->user->activeRoles()?->name ?? 'N/A' }}</th>
                                                <th>
                                                    <x-system.btn-edit text="edit" route="#" />
                                                    <x-system.btn-delete text="remove" route="#" />
                                                </th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </x-slot>
                            </x-system.table>
                            <button class="btn btn-danger btn-sm" v-on:click="deleteEmployeeFromGroup" type="button">remove
                                selected</button>
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
                    removedIndexes: [],
                };
            },
            methods: {
                addEmployee() {
                    if (this.chosen == '') {
                        return;
                    }
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
