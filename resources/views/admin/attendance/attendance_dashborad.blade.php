@extends('layouts.system')

@section('title', 'Attendance Dashboard')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Title -->
        {{-- <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Attendance Dashboard</h1>
            <a href="#" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Mark Attendance
            </a>
        </div> --}}

        <!-- Quick Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-sm-6">
                <div class="card text-white bg-success shadow">
                    <div class="card-body">
                        <h5 class="card-title">Present Today</h5>
                        <p class="display-6 fw-bold">{{ $presenties }}</p>
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="card text-white bg-danger shadow">
                    <div class="card-body">
                        <h5 class="card-title">Absent Today</h5>
                        <p class="display-6 fw-bold">{{ $absentees }}</p>
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="card text-white bg-warning shadow">
                    <div class="card-body">
                        <h5 class="card-title">Late Arrivals</h5>
                        <p class="display-6 fw-bold">{{ $lateComers }}</p>
                        <i class="bi bi-alarm-fill"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="card text-white bg-info shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Employees</h5>
                        <p class="display-6 fw-bold">{{ $employeesCount }}</p>
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Example (Attendance Trend) -->
        <div class="card shadow mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0 text-white">Attendance Trend (Last 7 Days)</h5>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart" height="100"></canvas>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white">Today's Attendance</h5>
            </div>

            <div class="card-body table-responsive">
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-md-12">
                        @livewire('admin.attendance-table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($daysOfWeek),
                datasets: [{
                    label: 'Present',
                    data: @json($presentData),
                    borderColor: 'green',
                    backgroundColor: 'rgba(0, 128, 0, 0.2)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Absent',
                    data: @json($absentData),
                    borderColor: 'red',
                    backgroundColor: 'rgba(255, 0, 0, 0.2)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Late',
                    data: @json($lateData),
                    borderColor: 'orange',
                    backgroundColor: 'rgba(255, 165, 0, 0.2)',
                    fill: true,
                    tension: 0.3
                }
            ]}
        });
    </script>
@endsection
