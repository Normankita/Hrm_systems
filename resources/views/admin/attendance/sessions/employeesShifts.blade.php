@extends('layouts.system')


@section('content')
    <div class="row justify-content-start" id="app">
        <div class="col-sm-12 col-md-12">
            <!-- bootstrap loading -->
            <div class="loading" id="loading">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="card">
                <div class="card-body" style="display: none" id="page">
                    <div class="row justify-content-center">
                        <div class="col-sm-12 col-md-12">
                            <div class="mb-3">
                                <h4>Employees Shifts/Sessions</h4>
                            </div>
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Employee</th>
                                        <th>Shift/Session</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(employee, index) in employees" :key="employee.id">
                                        <td>@{{ index + 1 }}</td>
                                        <td>@{{ employee.full_name }}</td>
                                        <td>@{{ employee.session_type }}</td>
                                        <td>
                                            <button v-on:click="editShift(index)"
                                                class="btn btn-outline-dark btn-sm p-0 px-1 mdi mdi-pencil">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-div">
            <div class="modal fade" id="edit-shift" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalFormTitle">
                                Edit Shift For: <span v-if="selectedEmployee">@{{ selectedEmployee.full_name }}</span>
                            </h5>
                            <div>
                                <p><span v-if="selectedEmployee"><b>Currently: @{{ selectedEmployee.session_type }}</b></span></p>
                            </div>
                            <button type="button" v-on:click="closeModal" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>

                        <div class="modal-body" v-if="selectedEmployee">
                            <div class="form-group">
                                <label>Current Shift</label>
                                <input type="text" class="form-control" :value="selectedEmployee.session_type" disabled>
                            </div>

                            <div class="form-group mt-2">
                                <label>New Shift</label>
                                <select class="form-control" v-model="newShift">
                                    <option v-for="shift in shifts" :value="shift.id">
                                        @{{ shift.session_type }}
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-pill" v-on:click="closeModal">Close</button>
                            <button v-if="newShift != null" v-on:click="submitEdit" type="submit"
                                class="btn btn-primary btn-pill">Save
                                Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        var app = Vue.createApp({
            data() {
                return {
                    isLoading: false,
                    shifts: @json($shifts),
                    employees: @json($employees),
                    selectedEmployee: null,
                    newShift: null,
                    authUserId: {{ auth()->user()->id }}
                }
            },
            methods: {
                editShift(index) {
                    this.selectedEmployee = this.employees[index]; // store selected employee
                    $('#edit-shift').modal('show'); // show modal
                },
                submitEdit() {
                    // submit the edit form
                    const updateUri = "{{ route('update.employee.shift') }}";
                    const employee_ID = this.selectedEmployee.empId;
                    const shift_id = this.newShift;
                    axios.get(updateUri, {
                        params: {
                            employee_id: employee_ID,
                            shift_id: shift_id,
                            editor: this.authUserId
                        }
                    }).then(response => {
                        // success
                        alert('Shift updated successfully');
                        // get shift where shift.id == shift_id
                        const updatedShift = this.shifts.find(shift => shift.id == shift_id);
                        // update the local data
                        this.selectedEmployee.session_type = updatedShift.session_type;
                        this.newShift = null;
                        this.closeModal();
                    }).catch(error => {
                        // error
                        alert('Error updating shift');
                    });
                },
                closeModal() {
                    $('#edit-shift').modal('hide');
                    this.selectedEmployee = null;
                }
            },
            mounted() {
                // hide loading
                document.getElementById('loading').style.display = 'none';
                document.getElementById('page').style.display = 'block';
            }
        }).mount('#app');
    </script>
@endsection
