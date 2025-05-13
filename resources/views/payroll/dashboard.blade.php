@php
    use App\Models\Employee;
    /**
     * Fetching essential data for this page here to avoid logic to averllap with
     * other dashboard pages.
     */
    $employeesCount = Employee::count();

    // select all employee whose today date falls betwween their leave dates
    $today = now();
    $employeesOnLeaveCount = Employee::whereHas('leaves', function ($query) use ($today) {
        $query->where('start_date', '<=', $today)->where('end_date', '>=', $today);
    })->count();

    // selection the last 10 leaves as the latest leave requests
    $latestEmployeeLeaveRequests = Employee::with('leaves')
        ->whereHas('leaves', function ($query) use ($today) {
            $query->where('start_date', '<=', $today)->where('end_date', '>=', $today);
        })
        ->latest()
        ->take(10)
        ->get();

    // fetching the leave types infos
    $leaveTypes = \App\Models\LeaveType::all();
@endphp

<style>
    .dashboard-card {
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
    }

    .stat-card {
        background: linear-gradient(45deg, #4e73df, #224abe);
        color: white;
    }

    .chart-container {
        position: relative;
        height: 300px;
    }
</style>

<div class="card">
    <div class="card-body">
        <div class="container-fluid py-4">
            <x-dashboard-title
            title="Payroll Manager" />
            <!-- Stats Cards Row -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase text-white mb-1">Total Employees</h6>
                                    <h2 class="mb-0">
                                        {{ $employeesCount }}
                                    </h2>
                                </div>
                                <i class="fas fa-users fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Summary Table -->
                            <div class="mt-4">
                                <h5 class="card-title"></h5>
                                <div class="mb-3 col-5">
                                    <a href="{{ route('hr.leave.type.index') }}" class="btn btn-primary btn-sm">
                                        manage leave Types
                                    </a>
                                </div>
                                <table class="table table-bordered leave-stats-table">
                                    <thead>
                                        <tr>
                                            <th>Leave Type</th>
                                            <th>Deduct From Annual Days</th>
                                            <th>Require Approval</th>
                                            <th>Created In</th>
                                        </tr>
                                    </thead>
                                    <tbody id="leaveSummaryTable">
                                        @foreach ($leaveTypes as $key => $leaveType)
                                            <tr>
                                                <td>{{ $leaveType->name }}</td>
                                                <td>{{ $leaveType->deducts_from_annual_leave ? 'yes' : 'no' }}</td>
                                                </td>
                                                <td>
                                                    {{ $leaveType->required_approval ? 'yes' : 'no' }}
                                                </td>
                                                <td>
                                                    {{ Carbon\Carbon::parse($leaveType->created_at)->format('d M-Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Set current date
    document.getElementById('current-date').textContent = new Date().toLocaleDateString();

    // Attendance Chart
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(attendanceCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Present',
                data: [145, 148, 142, 150, 146, 50, 30],
                borderColor: '#4e73df',
                tension: 0.1
            }, {
                label: 'Absent',
                data: [5, 2, 8, 0, 4, 100, 120],
                borderColor: '#e74a3b',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Department Distribution Chart
    const departmentCtx = document.getElementById('departmentChart').getContext('2d');
    new Chart(departmentCtx, {
        type: 'doughnut',
        data: {
            labels: ['IT', 'HR', 'Finance', 'Marketing', 'Operations'],
            datasets: [{
                data: [30, 20, 15, 25, 10],
                backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b', '#36b9cc']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
