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
            <x-dashboard-title title="HR" />

            <!-- Stats Cards Row -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase text-white mb-1">Total Employees</h6>
                                    <h2 class="mb-0">150</h2>
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
                                    <h2 class="mb-0">8</h2>
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
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>Vacation</td>
                                    <td>2025-04-20</td>
                                    <td><span class="badge bg-success">Approved</span></td>
                                </tr>
                                <tr>
                                    <td>Jane Smith</td>
                                    <td>Sick Leave</td>
                                    <td>2025-04-18</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                </tr>
                                <tr>
                                    <td>Mike Johnson</td>
                                    <td>Personal Leave</td>
                                    <td>2025-04-15</td>
                                    <td><span class="badge bg-success">Approved</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="col-12">
                    <div class="card p-4">
                        <!-- Summary Table -->
                        <div class="mt-4">
                            <h6>Leave Credits Summary</h6>
                            <table class="table table-bordered leave-stats-table">
                                <thead>
                                    <tr>
                                        <th>Leave Type</th>
                                        <th>Total Hours</th>
                                        <th>Used Hours</th>
                                        <th>Remaining Hours</th>
                                    </tr>
                                </thead>
                                <tbody id="leaveSummaryTable">
                                    <tr>
                                        <td>Vacation</td>
                                        <td>600</td>
                                        <td>100</td>
                                        <td>500</td>
                                    </tr>
                                    <tr>
                                        <td>Sick Leave</td>
                                        <td>500</td>
                                        <td>100</td>
                                        <td>400</td>
                                    </tr>
                                    <tr>
                                        <td>Personal Leave</ Pollock>
                                        <td>400</td>
                                        <td>100</td>
                                        <td>300</td>
                                    </tr>
                                    <tr>
                                        <td>Maternity Leave</td>
                                        <td>200</td>
                                        <td>50</td>
                                        <td>150</td>
                                    </tr>
                                    <tr>
                                        <td>Paternity Leave</td>
                                        <td>150</td>
                                        <td>30</td>
                                        <td>120</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <div class="col-xl-8 col-lg-7">
                    <div class="card dashboard-card mb-4">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold"><i class="fas fa-chart-line me-2"></i>Employee Attendance
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
                            <h6 class="m-0 font-weight-bold"><i class="fas fa-chart-pie me-2"></i>Department
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
                            <h6 class="m-0 font-weight-bold"><i class="fas fa-list me-2"></i>Recent Activities</h6>
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
                            <h6 class="m-0 font-weight-bold"><i class="fas fa-calendar-alt me-2"></i>Upcoming Events
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
