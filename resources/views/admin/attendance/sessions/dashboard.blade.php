@extends('layouts.system')

@section('content')
    <div class="container-fluid py-4">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Session Attendance</h4>
            <a href="{{ route(
                'admin.attendances.sessions.get.dashboard') }}">
                <button class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg"></i> Add Session
                </button>
            </a>
        </div>

        {{-- Filters --}}
        <div class="card mb-4">
            <div class="card-body">
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
            </div>
        </div>

        {{-- Table --}}
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Session Type</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Is Active</th>
                            <th>Attendance Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Example Row --}}
                        <tr>
                            <td>1</td>
                            <td>Morning</td>
                            <td>08:00 AM</td>
                            <td>12:00 PM</td>
                            <td><span class="badge bg-success">Yes</span></td>
                            <td>25</td>
                            <td>
                                <button class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        {{-- End Example Row --}}
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
