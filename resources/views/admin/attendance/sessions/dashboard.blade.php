@extends('layouts.system')

@section('content')
    <div class="container-fluid py-4">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Session Attendance Records</h4>
            {{-- <a href="{{ route('admin.attendances.sessions.index') }}">
                <button class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg"></i> Add Session
                </button>
            </a> --}}
        </div>

        {{-- Filters --}}
        <div class="card mb-4">
            {{-- <div class="card-body">
                <form class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Session Type</label>
                        <select class="form-select">
                            <option value="">All</option>
                            <option value="morning">Morning</option>
                            <option value="afternoon">Afternoon</option>
                            <option value="evening">Evening</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select">
                            <option value="">All</option>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-success w-100">
                            <i class="bi bi-search"></i> Filter
                        </button>
                    </div>
                </form>
            </div> --}}
        </div>

        {{-- Table --}}
        <div class="card">
            <div class="card-body p-0">
                @livewire('admin.attendance-records-table')
            </div>
        </div>

    </div>
@endsection
