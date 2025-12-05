@php
    use App\Http\Resources\UserResource;
@endphp

@extends('layouts.system')

@section('_links')
    <style>
        .dropdown-menu {
            border: 1px solid #ccc;
            padding: 0;
            margin: 0;
            list-style: none;
            background-color: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            padding: 8px 12px;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background-color: #f2f2f2;
        }
    </style>
@endsection

@section('content')
    <div class="row justify-content-center" id="loader">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="row justify-content-center d-none" id="emps">
        <div class="col-md-12">
            <div class="row justify-content-center" v-if="empSubmit">
                <div class="col-md-2">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row justify-contant-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div v-if="!empSubmit" class="col-md-12 mb-4">
                                <!-- DEFAULT CONTROLS -->
                                <div class="row mb-3">
                                    <div class="col-md-5">
                                        <label>Seach Employee</label>
                                        <div style="position: relative">
                                            <!-- Search input -->
                                            <input type="text" class="form-control" ref="searchInput"
                                                v-model="searchTerm" v-on:input="onSearch" v-on:focus="onSearch"
                                                v-on:blur="hideDropdownWithDelay" placeholder="Search..." />

                                            <!-- Dropdown list -->
                                            <ul v-if="showDropdown && filteredOptions.length" :style="dropdownStyles"
                                                class="dropdown-menu show">
                                                <li class="dropdown-item" v-for="(item, index) in filteredOptions"
                                                    :key="index" v-on:mousedown.prevent="selectItem(item)">
                                                    @{{ item.full_name }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button class="btn btn-sm btn-success" v-on:click="addAllEmployees"
                                            id="addAllButton">Add All</button>
                                    </div>
                                </div>

                                <!-- EMPLOYEE TABLE -->
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(employee, index) in selectedOptions" :key="employee.id">
                                            <td>@{{ employee.full_name }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-secondary"
                                                    v-on:click="()=> removeFromSelected(employee)">Remove</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="text-end mt-2">
                                    <button class="btn btn-sm btn-primary" type="button"
                                        v-on:click="submitSelectedEmployees">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@php
    $user = new UserResource(auth()->user());
@endphp

@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // receive employee data from backend
        const employees = {!! json_encode($employees) !!};
        const allowanceGroup = {!! json_encode($group) !!};
        const user = {!! json_encode($user) !!};

        const redirectToGroup = (uri) => {
            location.href = uri;
        }
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
            data() {
                return {
                    employees: employees,
                    defaultFrequency: '',
                    empSubmit: false,
                    group: allowanceGroup,
                    user: user,
                    error: null,
                    selectedOptions: [],
                    searchTerm: '',
                    selectedItem: null,
                    showDropdown: false,
                    filteredOptions: [],
                    options: employees,
                    dropdownStyles: {
                        position: 'absolute',
                        top: '0px',
                        left: '0px',
                        zIndex: 1000,
                        width: '100%',
                        maxHeight: '200px',
                        overflowY: 'auto'
                    }
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
                applyDefaultFrequency() {
                    this.employees.forEach(e => {
                        if (e.selected) e.frequency = this.defaultFrequency;
                    });
                },
                submitSelectedEmployees() {
                    this.empSubmit = true;
                    const selected = this.selectedOptions;

                    if (selected.length === 0) {
                        this.empSubmit = false;
                        alert("No employees selected.");
                        return;
                    }
                    let addingRoute = "{{ route('groups.add.employees.to.group', $group->id) }}";
                    axios.post(addingRoute, {
                            user: this.user,
                            employees: selected
                        })
                        .then(res => {
                            console.log(res.data);
                            if (res.data.status === 'success') {
                                this.selectedOptions = [];
                                this.empSubmit = false;
                                let redirectUrl = "{{  route('employee.manage.employee.allowances.groups.edit', $group->id) }}";
                                redirectToGroup(redirectUrl)

                            } else {
                                this.empSubmit = false;
                                this.error = "Failed to submit.";
                            }
                        })
                        .catch(() => {
                            this.empSubmit = false;
                            this.error = "Error occurred. Try again.";
                        });
                },



                onSearch() {
                    const input = this.$refs.searchInput;

                    if (!input) return;

                    // Get input position
                    const rect = input.getBoundingClientRect();
                    // Adjust dropdown position
                    this.dropdownStyles.top = `${input.offsetTop + input.offsetHeight}px`;
                    this.dropdownStyles.left = `${input.offsetLeft}px`;
                    this.dropdownStyles.width = `${input.offsetWidth}px`;

                    // Filter options
                    this.filteredOptions = this.options.filter(option =>
                        option.full_name.toLowerCase().includes(this.searchTerm.toLowerCase())
                    );

                    this.showDropdown = true;
                },
                selectItem(item) {
                    this.selectedItem = "";
                    this.searchTerm = "";
                    this.showDropdown = false;


                    const doesExists = this.selectedOptions.find(e => e.id === item.id);
                    if (!doesExists) {
                        this.selectedOptions.push(item);
                        this.options = this.options.filter(emp => emp.id != item.id);
                    }
                    document.getElementById('addAllButton').focus();

                },
                hideDropdownWithDelay() {
                    setTimeout(() => {
                        this.showDropdown = false;
                    }, 150);
                },
                removeFromSelected(employee) {
                    this.selectedOptions = this.selectedOptions.filter(e => e.id !== employee.id);
                    this.options.push(employee);
                },
                addAllEmployees() {
                    this.options.map(opt => this.selectedOptions.push(opt))
                    this.options = [];
                }
            },

        }).mount('#emps');
    </script>
@endsection
