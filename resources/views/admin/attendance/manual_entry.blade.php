<!-- resources/views/attendance/manual-entry.blade.php -->

@extends('layouts.system')

@section('content')
    <div class="container">
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
                    <a href="{{ route('admin.attendances.daily.page') }}" class="btn btn-primary">
                        View Daily Attendance
                    </a>
                    <x-system.modal-button id="groupSelection" text="MARK ALL" />
                    <x-system.modal size="modal-xl" id="groupSelection">
                        <div class="row justify-content-center">
                            <div class="col-sm-12 col-md-6">
                                <table class="table table table-responsive dt-table">
                                    <label for="allSelector">Select All</label>
                                    <input type="checkbox" id="all-checker">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Department</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employees as $employee)
                                            <tr>
                                                <td>
                                                    @if ($employee->userStatus == 'leave')
                                                    <input type="checkbox" class="row-checker">
                                                </td>
                                                <td>{{ $employee->full_name }}</td>
                                                <td>{{ $employee->department->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </x-system.modal>
                </div>
                <form id="manualEntryForm" method="POST" action="{{ route('admin.attendances.manual.entry.store') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="employee_id" class="form-label">Employee</label>
                            @livewire('employee-search')
                        </div>
                        <div class="col-md-4">
                            <label for="date" class="form-label">Date</label>
                            <input readonly name="date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                type="date" class="form-control" id="date">
                            @error('date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-select" id="status">
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="leave">Leave</option>
                                <option value="late">Late</option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Time Inputs -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="check_in" class="form-label">Check In</label>
                            <input name="check_in" type="time" class="form-control" id="check_in">
                            @error('check_in')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="check_out" class="form-label">Check Out</label>
                            <input name="check_out" type="time" class="form-control" id="check_out">
                            @error('check_out')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="remarks" class="form-control" id="notes" rows="2"></textarea>
                        @error('remarks')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="text-end">
                        <button type="reset" class="btn btn-sm btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-sm btn-primary">Save Entry</button>
                    </div>
                </form>
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
                                    <x-system.btn-edit route="#" />
                                    <x-system.btn-delete route="#" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let employeeTable = new TableSelectionHandler(".dt-table", "#all-checker");
    </script>
@endsection
