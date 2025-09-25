<!-- resources/views/attendance/manual-entry.blade.php -->

@extends('layouts.system')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="container" id="app">
                <h2 class="mb-4">Manual Attendance Entry</h2>

                <!-- Alert Placeholder -->
                <div class="alert alert-success d-none" role="alert" id="successAlert">
                    Attendance entry saved successfully!
                </div>

                <!-- Attendance Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <strong>Add Manual Entry</strong>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="{{ route('employee.manage.attendance.dailyAttendance') }}" class="btn btn-primary mx-2">
                                View Daily Attendance
                            </a>
                            <x-system.modal-button id="groupSelection" text="MARK" />
                            <x-system.modal size="modal-xl" id="groupSelection">
                                <div class="row justify-content-center">
                                    <div class="col-sm-12 col-md-12">
                                        <div v-if="loading">
                                            <div class="row justify-content-center">
                                                <div class="col-md-6">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                    Processing...
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="mb-3">
                                            <button v-if="!loading" class="btn btn-primary btn-sm mx-2" v-on:click="markSelected()"
                                                type="button">
                                                mark selected
                                            </button>
                                        </div>

                                        <div v-if="isCheckIn" class="mb-3">
                                            <label for="time" class="form-label">Time</label>
                                            <input type="time" class="form-control" id="check_in"
                                                v-model="time">

                                            <div class="mb-3">
                                                <label for="state" class="form-label">Type</label>
                                                <select v-model="type" class="form-control" id="">
                                                    <option value="check_in">Check In</option>
                                                    <option value="check_out">Check Out</option>
                                                </select>
                                            </div>

                                        </div>
                                        <div v-if="type == 'check_in'" class="mb-3">
                                            <label for="state form-label">State</label>
                                            <select v-model="state" class="form-control" id="">
                                                <option value="present">Present</option>
                                                <option value="late">Late</option>
                                                <option v-if="!type && !time" value="absent">Absent</option>
                                                <option v-if="!type && !time" value="leave">Leave</option>
                                            </select>
                                        </div>
                                        <table class="table table table-responsive dt-table">
                                            <label class="p-2" for="allSelector">Select All</label>
                                            <input type="checkbox" id="all-checker">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>for</th>
                                                    <th>Department</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($employees as $employee)
                                                    <tr>
                                                        <td>
                                                            @if ($employee->state == 'active')
                                                                <input value="{{ $employee->id }}" type="checkbox"
                                                                    class="row-checker">
                                                            @endif
                                                        </td>
                                                        <td>{{ $employee->state }}</td>
                                                        <td>{{ $employee->full_name }}</td>
                                                        <td>{{ $employee->intend }}</td>
                                                        <td>{{ $employee->department->name }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </x-system.modal>
                        </div>
                    </div>
                </div>

                <!-- Manual Entry Records Table -->
                <div class="card">
                    <div class="card-header">
                        <strong>Manual Attendance Details</strong>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-sm dt-table table-bordered">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <th>Employee</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($todayAttendance as $attendance)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $attendance->employee->full_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('Y-m-d') }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'absent' ? 'danger' : ($attendance->status == 'late' ? 'warning' : 'info')) }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}
                                        </td>
                                        <td>{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-' }}
                                        </td>
                                        <td>
                                            <x-system.modal-button id="editAttendance{{ $attendance->id }}" text="Edit"
                                                class="btn btn-outline-primary btn-sm" textColor="text-primary"
                                                icon="mdi mdi-pencil" />

                                            <form action="{{ route('admin.attendances.delete', $attendance->id) }}"
                                                method="POST" class="d-inline"
                                                id="deleteAttendanceForm{{ $attendance->id }}">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button" class="btn btn-outline-danger p-1 mdi mdi-delete"
                                                    onclick="event.preventDefault(); if (confirm('Are you sure you want to delete this attendance record?')) { document.getElementById('deleteAttendanceForm{{ $attendance->id }}').submit(); }">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @foreach ($todayAttendance as $attendance)
                    <x-system.modal size="modal-lg" id="editAttendance{{ $attendance->id }}">
                        <form id="editAttendanceForm{{ $attendance->id }}"
                            action="{{ route('admin.attendances.update', $attendance->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Employee</label>
                                <input type="text" class="form-control" id="employee_id" name="employee_id"
                                    value="{{ $attendance->employee->full_name }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="check_in" class="form-label">Check In</label>
                                <input name="check_in" type="time" class="form-control" id="check_in"
                                    value="{{ $attendance->check_in_time }}">
                                @error('check_in')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="check_out" class="form-label">Check Out</label>
                                <input name="check_out" type="time" class="form-control" id="check_out"
                                    value="{{ $attendance->check_out_time }}">
                                @error('check_out')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" class="form-select" id="status">
                                    <option value="present" {{ $attendance->status == 'present' ? 'selected' : '' }}>
                                        Present
                                    </option>
                                    <option value="absent" {{ $attendance->status == 'absent' ? 'selected' : '' }}>Absent
                                    </option>
                                    <option value="late" {{ $attendance->status == 'late' ? 'selected' : '' }}>Late
                                    </option>
                                    <option value="leave" {{ $attendance->status == 'leave' ? 'selected' : '' }}>Leave
                                    </option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea name="remarks" class="form-control" id="notes" rows="2">{{ $attendance->remarks }}</textarea>
                                @error('remarks')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-sm btn-primary">Update
                                    Entry</button>
                            </div>
                        </form>
                    </x-system.modal>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let employeeTable = new TableSelectionHandler(".dt-table", "#all-checker");
        const app = Vue.createApp({
            data() {
                return {
                    loading: false,
                    type: '', // Bind to the check-in time input
                    time: '',
                    state: 'present',
                }
            },
            mounted() {
                console.log("mounted");
            },
            computed: {
                // Computed properties can be added here if needed
                isCheckIn() {
                    return this.state === 'present' || this.state === 'late';
                }
            },
            watch: {
                state(newVal) {
                    if (newVal === 'present' || newVal === 'late') {
                        this.check_in = ""
                    }
                }
            },
            methods: {
                markSelected() {
                    const storeUri = "{{ route('attendances.manual.entry.store') }}";
                    const employeeIds = employeeTable.getSelected();
                    const headers = {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    };
                    const requestData = {
                        employees_ids: employeeIds,
                        time: this.time,
                        type: this.type,
                        state: this.state // Use the Vue data property for state
                    };
                    this.loading = true;
                    axios.post(storeUri, requestData, {
                            headers
                        })
                        .then(response => {
                            if (response.data.success) {
                                alert(response.data.message);
                            } else {
                                this.loading = false;
                                alert(response.data.error);
                            }
                            console.log("Response:", response);
                        })
                        .catch(error => {
                            this.loading = false;
                            alert("An error occurred while processing your request.");
                        })
                        .finally(() => {
                            window.location.reload();
                        });
                }
            },
        }).mount("#app");
    </script>
@endsection
