{{-- resources/views/attendance/daily.blade.php --}}

@php
    use App\Enums\AttendanceStatus;
    use Carbon\Carbon;
@endphp

@extends('layouts.system')

@section('title', 'Daily Attendance')

@section('content')
    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Daily Attendance</h1>
            <button class="btn btn-primary">
                <i class="bi bi-download"></i> Export
            </button>
        </div>

        {{-- Filters --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ url()->current() }}" class="row g-3">
                    @csrf
                    <div class="col-md-3">
                        <label class="form-label">Date</label>
                        <input name="date" type="date" value="{{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}"
                            class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Department</label>
                        <select name="department" class="form-select">
                            <option selected>All</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ $selectedDepartment == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option selected>All</option>
                            @foreach (AttendanceStatus::cases() as $status)
                                <option value="{{ $status->value }}"
                                    {{ $selectedStatus == $status->value ? 'selected' : '' }}>{{ ucfirst($status->value) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Stats Overview --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Total Employees</h6>
                        <h3>{{ $employees->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <h6 class="text-success">Present</h6>
                        <h3>{{ $present->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-danger">
                    <div class="card-body">
                        <h6 class="text-danger">Absent</h6>
                        <h3> {{ $absent->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-warning">
                    <div class="card-body">
                        <h6 class="text-warning">Late</h6>
                        <h3>{{ $late->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Attendance Table --}}
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Attendance List</h5>
                <div class="table-responsive">
                    <table class="table dt-table table-striped align-middle">
                        <thead>
                            <tr>
                                <td>#</td>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (['present' => $present, 'absent' => $absent, 'late' => $late] as $collection)
                                @foreach ($collection as $attendance)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $attendance->employee->full_name }}</td>
                                        <td>{{ $attendance->department?->name ?? 'N/A'}}</td>
                                        <td><span class="badge
                                            @if($attendance->status == 'present')
                                                bg-success
                                            @elseif($attendance->status == 'late')
                                                bg-warning
                                            @elseif($attendance->status == 'leave')
                                                bg-dark text-white
                                            @else
                                                bg-danger text-white
                                            @endif
                                            ">{{ $attendance->status }}</span></td>
                                        <td>{{ Carbon::parse($attendance->check_in_time)
                                        ->format('H:ia')  }}</td>
                                        <td>{{ Carbon::parse($attendance->check_out_time)
                                        ->format('H:ia') }}</td>
                                        <td>On time</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
