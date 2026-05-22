@php
    use App\Http\Resources\UserResource;
    $user = new UserResource(auth()->user());
@endphp

@extends('layouts.system')

@section('content')
    <div class="row justify-content-center" id="loader">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div id="emps" class="d-none">
        <div class="row justify-content-center">
            <div class="row justify-content-center" v-if="empSubmit">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row justify-content-center">
                    <div v-if="!empSubmit && !allowance_id" class="col-md-12 alert alert-info text-center ">
                        Please select an allowance to begin assigning values.
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row justify-content-start mb-3">
                    <div class="col-md-6">
                        <label>Choose an Allowance</label>
                        <select class="form-control" v-model="allowance_id">
                            <option value="" disabled>-- Select allowance --</option>
                            <option v-for="allowance in allowances" :key="allowance.id" :value="allowance.id">
                                @{{ allowance.name }}
                            </option>
                        </select>
                    </div>

                </div>
            </div>
            <div v-if="!empSubmit && allowance_id" class="col-md-12 mb-4">
                <div class="row mb-3" v-if="selectedEmployees.length > 0">
                    <div class="col-md-3">
                        <label>Default Amount</label>
                        <input type="number" class="form-control" v-model="defaultAmount" v-on:input="applyDefaultAmount">
                    </div>
                    <div class="col-md-3">
                        <label>Default Frequency</label>
                        <select class="form-control" v-model="defaultFrequency" v-on:change="applyDefaultFrequency">
                            <option value="" disabled>-- Select Frequency --</option>
                            <option v-for="frequency in frequencies" :key="frequency.id" :value="frequency.id">
                                @{{ frequency.name }}
                            </option>
                        </select>
                    </div>
                    {{-- <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-sm btn-secondary" v-on:click="selectAll">Select All</button>
                </div> --}}
                </div>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-sm p-5">
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
                                    <td><input type="checkbox" v-model="employee.selected"></td>
                                    <td>@{{ employee.full_name }}</td>
                                    <td><input type="number" class="form-control" v-model="employee.amount"></td>
                                    <td>
                                        <select class="form-control" v-model="employee.frequency_id">
                                            <option value="">--</option>
                                            <option v-for="frequency in frequencies" :key="frequency.id"
                                                :value="frequency.id">
                                                @{{ frequency.name }}
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="text-end mt-2">
                    <button v-if="selectedEmployees.length > 0" class="btn btn-sm btn-primary" type="button"
                        v-on:click="submitSelectedEmployees" :disabled="empSubmit">
                        Submit
                    </button>
                </div>
            </div>
        </div>

        <div class="row justify-content-start">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <x-system.table class="dt-table">
                            <x-slot name="head">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Allowance Category</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                            </x-slot>
                            <x-slot name="body">
                                <tbody>
                                    @foreach ($categories as $key => $category)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                <x-system.btn-view :route="route('admin.employee.allowances.groups.allowanceDetails', [
                                                    $group->id,
                                                    $category->id,
                                                ])" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </x-slot>
                        </x-system.table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        const employees = @json($employees);
        const allowanceGroup = @json($group);
        const user = @json($user);
        const frequencies = @json($frequencies);
        const allowances = @json($allowances);

        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute(
            'content');

        const app = Vue.createApp({
            mounted() {
                this.$nextTick(() => {
                    // DOM is fully rendered and reactive updates are done
                    let domObject = document.getElementById('emps');
                    domObject.classList.remove('d-none');
                    domObject.classList.add('d-block');

                    document.getElementById('loader').classList.add('d-none');
                    document.getElementById('loader').classList.remove('d-block');
                });
            },
            computed: {
                selectedEmployees() {
                    return this.employees.filter(e => e.selected);
                }
            },
            data() {
                return {
                    employees: employees.map(e => ({
                        ...e,
                        selected: false,
                        amount: e.amount || null,
                        frequency_id: e.frequency_id || null,
                    })),
                    frequencies: frequencies,
                    allowances: allowances,
                    defaultAmount: '',
                    defaultFrequency: '',
                    allowance_id: null,
                    empSubmit: false,
                    group: allowanceGroup,
                    user: user,
                    error: null
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
                    const selectedFreq = this.frequencies.find(f => f.id === this.defaultFrequency);
                    if (!selectedFreq) return;
                    this.employees.forEach(e => {
                        if (e.selected) e.frequency_id = selectedFreq.id;
                    });
                },
                submitSelectedEmployees() {
                    this.empSubmit = true;

                    if (!this.allowance_id) {
                        this.empSubmit = false;
                        alert("Please select an allowance.");
                        return;
                    }

                    const selected = this.employees
                        .filter(e => e.selected)
                        .map(e => ({
                            id: e.id,
                            full_name: e.full_name,
                            amount: e.amount,
                            frequency_id: e.frequency_id
                        }));

                    if (selected.length === 0) {
                        this.empSubmit = false;
                        alert("No employees selected.");
                        return;
                    }

                    let submitingAllowance = "{{ route('groups.assig.allowance.to.group', $group->id) }}"
                    axios.post(submitingAllowance, {
                        user: this.user,
                        employees: selected,
                        allowance_id: this.allowance_id,
                    })
                .then(res => {
                    if (res.status == 200) {
                        if (res.data.status === 'success') {
                            alert('Employee Added Successfully...');
                            location.reload();
                        } else {
                            this.empSubmit = false;
                            this.error = "Failed to submit.";
                            alert(this.error);
                        }
                    } else {
                        alert('bad request please try again');
                    }
                })
                .catch(() => {
                    this.empSubmit = false;
                    this.error = "Error occurred. Try again.";
                    alert(this.error);
                });
            }
        }
        }).mount('#emps');
    </script>
@endsection
