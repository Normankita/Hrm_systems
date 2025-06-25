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

    <div v-if="!empSubmit" class="col-md-12 mb-4">
        <!-- DEFAULT CONTROLS -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label>Default Amount</label>
                <input type="number" class="form-control" v-model="defaultAmount" v-on:input="applyDefaultAmount">
            </div>
            <div class="col-md-3">
                <label>Default Frequency</label>
                <select class="form-control" v-model="defaultFrequency" v-on:change="applyDefaultFrequency">
                    <option value="">-- Select Frequency --</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-sm btn-secondary" v-on:click="selectAll">Select All</button>
            </div>
        </div>

        <!-- EMPLOYEE TABLE -->
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th><input type="checkbox" v-on:change="toggleAll($event)"></th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Frequency</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(employee, index) in employees" :key="employee.id">
                    <td>
                        <input type="checkbox" v-model="employee.selected">
                    </td>
                    <td>@{{ employee.full_name }}</td>
                    <td>
                        <input type="number" class="form-control" v-model="employee.amount">
                    </td>
                    <td>
                        <select class="form-control" v-model="employee.frequency">
                            <option value="">--</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="text-end mt-2">
            <button class="btn btn-sm btn-primary" type="button" v-on:click="submitSelectedEmployees">Submit</button>
        </div>
    </div>
</div>
@endsection

@php
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
                employees: employees.map(e => ({ ...e, selected: false, amount: null, frequency: null })),
                defaultAmount: '',
                defaultFrequency: '',
                empSubmit: false,
                group: allowanceGroup,
                user: user,
                error: null,
            };
        },
        methods: {
            selectAll() {
                this.employees.forEach(e => e.selected = true);
            },
            toggleAll(event) {
                const checked = event.target.checked;
                this.employees.forEach(e => e.selected = checked);
            },
            applyDefaultAmount() {
                this.employees.forEach(e => {
                    if (e.selected) e.amount = this.defaultAmount;
                });
            },
            applyDefaultFrequency() {
                this.employees.forEach(e => {
                    if (e.selected) e.frequency = this.defaultFrequency;
                });
            },
            submitSelectedEmployees() {
                this.empSubmit = true;
                const selected = this.employees
                    .filter(e => e.selected)
                    .map(e => ({
                        id: e.id,
                        full_name: e.full_name,
                        amount: e.amount,
                        frequency: e.frequency
                    }));

                if (selected.length === 0) {
                    this.empSubmit = false;
                    alert("No employees selected.");
                    return;
                }

                axios.post(`/api/groups/add/employees/to/group/${this.group.id}`, {
                    user: this.user,
                    employees: selected
                })
                .then(res => {
                    if (res.data.status === 'success') {
                        location.reload();
                    } else {
                        this.empSubmit = false;
                        this.error = "Failed to submit.";
                    }
                })
                .catch(() => {
                    this.empSubmit = false;
                    this.error = "Error occurred. Try again.";
                });
            }
        }
    }).mount('#emps');
</script>
@endsection
