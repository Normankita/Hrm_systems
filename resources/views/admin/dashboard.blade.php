<div class="card card-default">
    <div class="px-6">
        <!-- Top Statistics -->
        <div class="row">
            <!-- Payroll This Month -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-default ">
                    <div class="card-header">
                        <h2>{{number_format($dashboard['total_payrolls'])}} TZS</h2> <!-- Replace with dynamic total payroll value -->
                        <div class="sub-title">
                            <span>Payroll This Month</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statutory Deductions -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-default">
                    <div class="card-header">
                        <h2>{{number_format($dashboard['total_deductions'])}} TZS</h2> <!-- Replace with dynamic deductions value -->
                        <div class="sub-title">
                            <span>Total Deductions</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employees on Leave -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-default">
                    <div class="card-header">
                        <h2>{{ $dashboard['employees_on_leave'] }}</h2> <!-- Replace with dynamic count -->
                        <div class="sub-title">
                            <span>Employees on Leave</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Employees -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-default">
                    <div class="card-header">
                        <h2>{{ $dashboard['total_employees'] }}</h2> <!-- Replace with dynamic total employee count -->
                        <div class="sub-title">
                            <span>Total Employees</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Second Row: Charts and Insights -->
        <div class="row ">
            <!-- Attendance Chart -->
            <div class="col-xl-8">
                <div class="card card-default">
                    <div class="card-header">
                        <h2>Weekly Attendance Overview</h2>
                    </div>
                    <div class="card-body">
                        <canvas id="attendanceChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Payroll Alert / Approvals -->
            <div class="col-xl-4">
                <div class="card card-default">
                    <div class="card-header">
                        <h2>Payroll Alerts</h2>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li>Payroll due in {{$dashboard['days_left_for_payment']}} days</li>
                            <li>{{$dashboard['pending_payrolls']}} pending payroll approvals</li>
                            <li>Last payroll run: {{$dashboard['last_payroll_period']}}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Third Row: Employee Table -->
        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="card card-default">
                    <div class="card-header">
                        <h2>Recent Onboarded Employees</h2>
                    </div>
                    <div class="card-body">
                        <table class="table dt-table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Date Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach ($dashboard['recent_employees'] as $employee )
                                <tr>
                                    <td>{{ $employee->full_name }}</td>
                                    <td>{{ $employee->department->name }}</td>
                                    <td>{{ $employee->user->roles->pluck('name')->join(', ') }}</td>
                                    <td>{{ $employee->date_of_hire }}</td>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($dashboard['daysOfWeek']),
                datasets: [{
                    label: 'Present',
                    data: @json($dashboard['presentData']),
                    borderColor: 'green',
                    backgroundColor: 'rgba(0, 128, 0, 0.2)',
                    fill: true,
                    tension: 0.3
                }, {
                    label: 'Absent',
                    data: @json($dashboard['absentData']),
                    borderColor: 'red',
                    backgroundColor: 'rgba(255, 0, 0, 0.2)',
                    fill: true,
                    tension: 0.3
                }]
            }
        });
    </script>
