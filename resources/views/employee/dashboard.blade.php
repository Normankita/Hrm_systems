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

<div class="card card-default">
    <x-dashboard-title title="EMPLOYEE" />
    <div class="px-6">
        <!-- Top Statistics -->
        <div class="row">
            <div class="col-12">
                <div class="py-6 bg-gray-100 min-h-screen ">
                    {{-- Employee Information --}}

                    <div class=" shadow-lg px-6 bg-white rounded-lg">
                        <div class="card-header bg-white">
                            <h2 class="text-gray-800 font-semibold">Personal Information</h2>
                        </div>
                        <div class="row mb-4 ">
                            <!-- Employee Status -->
                            <div class="bg-white shadow rounded-2xl p-4 col-xl-3 col-md-6 mb-4">
                                <h2 class="text-gray-600 text-sm mb-1">Status</h2>
                                <p class="text-xl font-semibold text-green-600">
                                    {{ $employee->currentStatus->status->name }}
                                </p>
                            </div>

                            <!-- Net Salary -->
                            <div class="bg-white shadow rounded-2xl p-4 col-xl-3 col-md-6 mb-4">
                                <h2 class="text-gray-600 text-sm mb-1">Net Salary (This Month)</h2>
                                <p class="text-xl font-semibold text-blue-600">TZS {{ $dashboard['net_salary'] }}
                                    <!-- Replace with dynamic salary -->
                                </p>
                            </div>

                            <!-- Today's Attendance -->
                            <div class="bg-white shadow rounded-2xl p-4 col-xl-3 col-md-6 mb-4">
                                <h2 class="text-gray-600 text-sm mb-1">Today’s Attendance</h2>
                                <p class="text-md text-gray-800">Clocked in at 08:30 AM
                                    <!-- Replace with dynamic attendance time -->
                                </p>
                            </div>

                            <!-- Leave Balance -->
                            <div class="bg-white shadow rounded-2xl p-4 col-xl-3 col-md-6 mb-4">
                                <h2 class="text-gray-600 text-sm mb-1">Leave Balance</h2>
                                <p class="text-md text-gray-800">Annual: {{ $dashboard['leave_balance'] }} days
                                    <!-- Replace with dynamic annual leave for Employee that is from the table -->
                                </p>
                            </div>
                        </div>

                        <div class="py-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Recent Payslips -->
                            <div class="bg-white shadow rounded-2xl p-6">
                                <h3 class="text-lg font-semibold text-gray-700 mb-4">Recent Payslips</h3>
                                @if ($dashboard['recent_payrolls']->isNotEmpty())
                                    <ul>
                                        @foreach ($dashboard['recent_payrolls'] as $payroll)
                                            <li class="border-b py-2 flex justify-between">
                                                <span>{{ $payroll->period }}</span>
                                                <a href="{{ asset('storage/' . $payroll->payslip_path) }}"
                                                    class="text-blue-600 text-sm">Download</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <!-- Recent Leave Requests -->
                            <div class="bg-white shadow rounded-2xl p-6">
                                <h3 class="text-lg font-semibold text-gray-700 mb-4">Recent Leave Requests</h3>
                                <ul>
                                    @if ($dashboard['recent_leaves']->isNotEmpty())
                                        @foreach ($dashboard['recent_leaves'] as $leave)
                                            <li class="border-b py-2">
                                                <span class="text-sm">{{ $leave->leaveType->name }}:
                                                    {{ $leave->start_date }} to {{ $leave->end_date }}</span>
                                                <span
                                                    class="ml-2 text-xs {{ $leave->status == 'Approved' ? 'text-green-500' : ($leave->status == 'Pending' ? 'text-yellow-500' : 'text-red-500') }}">
                                                    ({{ $leave->status }})
                                                </span>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="text-gray-500">No recent leave requests.</li>
                                    @endif
                                    <!-- Loop through dynamic leave requests -->
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Management information --}}

                    <div class="card my-6 shadow-lg">
                        <div class="card-header bg-white">
                            <h2 class="text-gray-800 font-semibold">Management Information</h2>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid py-4">

                                <!-- Stats Cards Row -->
                                <div class="row mb-4">
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card dashboard-card stat-card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="text-uppercase text-white mb-1">Total Employees</h6>
                                                        <h2 class="mb-0">
                                                            {{ $dashboard['total_employees'] }}
                                                        </h2>
                                                    </div>
                                                    <i class="fas fa-users fa-2x opacity-50"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card dashboard-card stat-card"
                                            style="background: linear-gradient(45deg, #1cc88a, #13855c);">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="text-uppercase text-white mb-1">Present Today</h6>
                                                        <h2 class="mb-0">142</h2>
                                                    </div>
                                                    <i class="fas fa-user-check fa-2x opacity-50"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card dashboard-card stat-card"
                                            style="background: linear-gradient(45deg, #f6c23e, #dda20a);">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="text-uppercase text-white mb-1">On Leave</h6>
                                                        <h2 class="mb-0">
                                                            {{ $dashboard['employees_on_leave'] }}
                                                        </h2>
                                                    </div>
                                                    <i class="fas fa-calendar-minus fa-2x opacity-50"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card dashboard-card stat-card"
                                            style="background: linear-gradient(45deg, #e74a3b, #be2617);">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="text-uppercase text-white mb-1">Open Positions</h6>
                                                        <h2 class="mb-0">5</h2>
                                                    </div>
                                                    <i class="fas fa-briefcase fa-2x opacity-50"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <!-- Recent Activity Table -->
                                    <div class="col-sm-12">
                                        <div class="card p-4">
                                            <h5 class="card-title">Recent Leave Requests</h5>
                                            <div class="mb-3 col-5">
                                                <a href="#" class="btn btn-primary btn-sm">
                                                    manage leave requests
                                                </a>
                                            </div>
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Employee</th>
                                                        <th>Leave Type</th>
                                                        <th>Start Date</th>
                                                        <th>End Date</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($dashboard['recent_leave_request'] as $employee)
                                                        @foreach ($employee->leaves as $leave)
                                                            <tr>
                                                                <td>{{ $leave->employee->full_name }}</td>
                                                                <td>{{ $leave->leaveType->name }}</td>
                                                                <td>{{ Carbon\Carbon::parse($leave->start_date)->format('d M-Y') }}
                                                                </td>
                                                                <td>{{ Carbon\Carbon::parse($leave->end_date)->format('d M-Y') }}
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="
                                                @if ($leave->status == 'approved') bg-success
                                                @elseif ($leave->status == 'rejected')
                                                    bg-danger
                                                @else
                                                    bg-warning @endif
                                                badge text-white">
                                                                        {{ ucfirst($leave->status) }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                            </table>`
                                        </div>
                                    </div>


                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <!-- Summary Table -->
                                                <div class="mt-4">
                                                    <h5 class="card-title">Leave Types Preview</h5>
                                                    <div class="mb-3 col-5">
                                                        <a href="#" class="btn btn-primary btn-sm">
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
                                                            @foreach ($dashboard['leave_types'] as $key => $leaveType)
                                                                <tr>
                                                                    <td>{{ $leaveType->name }}</td>
                                                                    <td>{{ $leaveType->deducts_from_annual_leave ? 'yes' : 'no' }}
                                                                    </td>
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

                                <!-- Charts Row -->
                                <div class="row mb-4">
                                    <div class="col-xl-8 col-lg-7">
                                        <div class="card dashboard-card mb-4">
                                            <div class="card-header">
                                                <h6 class="m-0 font-weight-bold"><i
                                                        class="fas fa-chart-line me-2"></i>Employee Attendance
                                                    Overview
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="chart-container">
                                                    <canvas id="attendanceChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-5">
                                        <div class="card dashboard-card mb-4">
                                            <div class="card-header">
                                                <h6 class="m-0 font-weight-bold"><i
                                                        class="fas fa-chart-pie me-2"></i>Department
                                                    Distribution</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="chart-container">
                                                    <canvas id="departmentChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Recent Activities and Upcoming Events -->
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card dashboard-card mb-4">
                                            <div class="card-header">
                                                <h6 class="m-0 font-weight-bold">
                                                    <i class="fas fa-list me-2"></i>Recent Activities
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Employee</th>
                                                                <th>Activity</th>
                                                                <th>Time</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>John Doe</td>
                                                                <td>Submitted Leave Request</td>
                                                                <td>2 hours ago</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Jane Smith</td>
                                                                <td>Completed Training</td>
                                                                <td>3 hours ago</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Mike Johnson</td>
                                                                <td>Updated Profile</td>
                                                                <td>5 hours ago</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="card dashboard-card mb-4">
                                            <div class="card-header">
                                                <h6 class="m-0 font-weight-bold"><i
                                                        class="fas fa-calendar-alt me-2"></i>Upcoming Events
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="list-group">
                                                    <a href="#" class="list-group-item list-group-item-action">
                                                        <div class="d-flex w-100 justify-content-between">
                                                            <h6 class="mb-1">Team Building Event</h6>
                                                            <small>3 days left</small>
                                                        </div>
                                                        <p class="mb-1">Annual team building at Central Park</p>
                                                    </a>
                                                    <a href="#" class="list-group-item list-group-item-action">
                                                        <div class="d-flex w-100 justify-content-between">
                                                            <h6 class="mb-1">Performance Reviews</h6>
                                                            <small>5 days left</small>
                                                        </div>
                                                        <p class="mb-1">Q2 Performance Review Meetings</p>
                                                    </a>
                                                    <a href="#" class="list-group-item list-group-item-action">
                                                        <div class="d-flex w-100 justify-content-between">
                                                            <h6 class="mb-1">Training Session</h6>
                                                            <small>1 week left</small>
                                                        </div>
                                                        <p class="mb-1">New Software Training for IT Department</p>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
