@extends('layouts.system')

@section('content')
    <div class="row justify content-center" id="emps">
        <div class="col-12">
            <!-- Card for displaying three catregories of allawances
                                        which are group, individual, category allowances -->
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <x-system.modal-button text="Disburse Allowance" id="disburse-allowance"></x-system.modal-button>
                                    <x-system.modal title="Disburse Based On" id="disburse-allowance">
                                        <form
                                        action="{{ route('employee.manage.disbursements.create') }}"
                                        method="GET">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="basedOn">Disburse Based On</label>
                                                    <select v-model="categoryOpted" v-on:change="fetchRecentCategory($event)" class="form-control"
                                                    name="basedOn" id="basedOn"
                                                        required>
                                                        <option value="all">All</option>
                                                        <option value="group"
                                                            {{ session('category') == 'group' ? 'selected' : '' }}>
                                                            Group</option>
                                                        <option value="individual"
                                                            {{ session('category') == 'individual' ? 'selected' : '' }}>
                                                            Individual</option>
                                                        <option value="category"
                                                            {{ session('category') == 'category' ? 'selected' : '' }}>
                                                            Category</option>
                                                    </select>
                                                    @error('basedOn')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div v-if="optionsEmployees.length > 0" class="col-md-12">
                                                    <label for="employees">Choose Employees</label>
                                                    <input type="text" v-on:change="searchEmployee($event)">
                                                </div>
                                                <div class="col-md-12 mt-3">
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        start
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </x-system.modal>
                            </div>
                            <h3 class="mb-2">Allowance Disbursement Directory</h3>
                        </div>

                        <!-- at right side, add search button with search mdi icon, a toggle buttons and a submit button -->
                        <div class="col-md-8">
                            <!-- aa search button with input search field with date picker -->
                            <select class="form-control" name="basedOn"
                                id="basedOn" required>
                                <option value="all"
                                    {{ session('category') == 'all' || session('category') == null ? 'selected' : '' }}>All
                                </option>
                                <option value="group" {{ session('category') == 'group' ? 'selected' : '' }}>Group</option>
                                <option value="individual" {{ session('category') == 'individual' ? 'selected' : '' }}>
                                    Individual</option>
                                <option value="category" {{ session('category') == 'category' ? 'selected' : '' }}>Category
                                </option>
                            </select>
                        </div>

                        <div class="col-12" id="individual">
                            <h4 class="mt-3">Individual Allowances</h4>
                            <table
                                class="table table-bordered table-hover align-middle
                                    text-nowrap">
                                <thead class="table-light text-dark">
                                    <tr>
                                        <th>Allowance</th>
                                        <th>Amount</th>
                                        <th>Disbursed Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(allowance, index) in individualBased" :key="index">
                                        <td>@{{ allowance.name }}</td>
                                        <td>@{{ allowance.amount }}</td>
                                        <td>@{{ allowance.disbursed_date }}</td>
                                        <td>
                                            <!-- Add action buttons if needed -->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-12" id="group">

                        </div>

                        <div class="col-12" id="category">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Fetching category string from session
        const category = "{{ session('category') }}";
        const app = Vue.createApp({
            data() {
                return {
                    categoryOpted: null,
                    category: category,
                    individualBased: [],
                    groupBased: [],
                    categoryBased: null,
                    optionsEmployees: [],
                    selectedEmployees: [],
                }
            },
            methods: {
                searchEmployee(event) {
                    console.log(event.target.value);
                },
                async fetchRecentCategory(event) {
                    const selectedCategory = event.target.value;
                    const response = await axios
                        .post("{{ route('fetch.employees') }}");
                    if (response.status === 200) {
                        const {data} = response;
                        this.optionsEmployees = data;
                    }
                },
            },
        }).mount('#emps');
    </script>
@endsection
