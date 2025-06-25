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
    <div class="row justify-content-center" id="emps">
        <div class="col-md-12">
            <div class="row justify-content-center" v-if="empSubmit">
                <div class="col-md-2">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="!empSubmit" class="col-md-12 mb-4">
            <!-- DEFAULT CONTROLS -->
            <div class="row mb-3">
                <div class="col-md-5">
                    <label>Seach Employee</label>
                    <select class="form-control" id="multiSelect" multiple="multiple">
                        <option v-for="employee in employees" :value="employee.id">
                            @{{ employee.full_name }}
                        </option>
                    </select>






                    <div style="position: relative">
                        <!-- Search input -->
                        <input type="text" class="form-control" ref="searchInput" v-model="searchTerm"
                            v-on:input="onSearch" v-on:focus="onSearch" v-on:blur="hideDropdownWithDelay"
                            placeholder="Search..." />

                        <!-- Dropdown list -->
                        <ul v-if="showDropdown && filteredOptions.length" :style="dropdownStyles"
                            class="dropdown-menu show">
                            <li class="dropdown-item" v-for="(item, index) in filteredOptions" :key="index"
                                v-on:mousedown.prevent="selectItem(item)">
                                @{{ item.full_name }}
                            </li>
                        </ul>
                    </div>






                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-sm btn-secondary" v-on:click="removeFromSelected">remove last</button>
                </div>
            </div>

            <!-- EMPLOYEE TABLE -->
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(employee, index) in selectedOptions" :key="employee.id">
                        <td>@{{ employee.full_name }}</td>
                    </tr>
                </tbody>
            </table>
            {{-- <div class="text-end mt-2">
                <button class="btn btn-sm btn-primary" type="button" v-on:click="submitSelectedEmployees">Submit</button>
            </div> --}}
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

        const app = Vue.createApp({
            data() {
                return {
                    employees: employees,
                    defaultAmount: '',
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
                    console.log(this.filteredOptions);
                    this.filteredOptions = this.options.filter(option =>
                        option.full_name.toLowerCase().includes(this.searchTerm.toLowerCase())
                    );

                    this.showDropdown = true;
                },
                selectItem(item) {
                    this.selectedItem = item;
                    this.searchTerm = item;
                    this.showDropdown = false;

                    const doesExists = this.selectedOptions.find(e => e.id === item.id);
                    if (!doesExists) {
                        this.selectedOptions.push(item);
                    }
                    console.log(this.selectedOptions);
                },
                hideDropdownWithDelay() {
                    setTimeout(() => {
                        this.showDropdown = false;
                    }, 150);
                }
                // removeFromSelected(employee) {
                //     // this.selectedOptions = this.selectedOptions.filter(e => e.id !== id);
                //     this.selectedOptions = this.selectedOptions.filter(e => e.id !== employee
                //     .id); // remove last selected option
                //     // after removing from the selected options, update the select2 to include the change
                //     const selectElement = $('#multiSelect');
                //     this.employees.forEach(employee => {
                //         const newOptions = this.selectedOptions.map(e => {
                //             if (e.id == employee.id) { return null}
                //             `<option value="${employee.id}">${employee.full_name}</option>`
                //     }).join('');
                //             selectElement.html(newOptions);
                //     });
                //     selectElement.select2(); // reinitialize select2
                //     console.log(this.selectedOptions);
                // }
            },
            mounted() {
                // Initialize Select2
                $('#multiSelect').select2();
                const self = this;
                // Listen for change and update Vue data
                $('#multiSelect').on('change', (e) => {
                    let empIds = $('#multiSelect').val(); // array of selected values
                    // does employee exist in the table
                    self.selectedOptions = empIds.map(id => {
                        const employee = self.employees.find(emp => {
                            // checking if emp.ikd is not undefines
                            return emp.id == id;
                        });
                        if (employee) {
                            return employee;
                        } else {
                            return null;
                        }
                    });
                });
            },
            beforeUnmount() {
                // Clean up
                $('#multiSelect').off('change');
                $('#multiSelect').select2('destroy');
            }
        }).mount('#emps');
    </script>
@endsection
