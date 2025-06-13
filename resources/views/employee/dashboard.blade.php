<div class="card card-default">
    <div class="px-6">
        <!-- Top Statistics -->
        <div class="row">

            <div class="col-12">
                <div class="p-6 bg-gray-100 min-h-screen">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Employee Status -->
                        <div class="bg-white shadow rounded-2xl p-4">
                            <h2 class="text-gray-600 text-sm mb-1">Status</h2>
                            <p class="text-xl font-semibold text-green-600">
                                {{ $employee->currentStatus->status->name }}
                            </p>
                        </div>

                        <!-- Net Salary -->
                        <div class="bg-white shadow rounded-2xl p-4">
                            <h2 class="text-gray-600 text-sm mb-1">Net Salary (This Month)</h2>
                            <p class="text-xl font-semibold text-blue-600">TZS {{ $dashboard['net_salary']}}
                                <!-- Replace with dynamic salary -->
                            </p>
                        </div>

                        <!-- Today's Attendance -->
                        <div class="bg-white shadow rounded-2xl p-4">
                            <h2 class="text-gray-600 text-sm mb-1">Today’s Attendance</h2>
                            <p class="text-md text-gray-800">Clocked in at 08:30 AM
                                <!-- Replace with dynamic attendance time -->
                            </p>
                        </div>

                        <!-- Leave Balance -->
                        <div class="bg-white shadow rounded-2xl p-4">
                            <h2 class="text-gray-600 text-sm mb-1">Leave Balance</h2>
                            <p class="text-md text-gray-800">Annual: {{ $dashboard['leave_balance'] }} days
                                <!-- Replace with dynamic annual leave for Employee that is from the table -->
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Recent Payslips -->
                        <div class="bg-white shadow rounded-2xl p-6">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Recent Payslips</h3>
                            @if ($dashboard['recent_payrolls']->isNotEmpty())
                                <ul>
                                    @foreach ($dashboard['recent_payrolls'] as $payroll)
                                        <li class="border-b py-2 flex justify-between">
                                            <span>{{ $payroll->period }}</span>
                                                <a href="{{ asset('storage/' . $payroll->payslip_path) }}" class="text-blue-600 text-sm">Download</a>
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

            </div>
        </div>
    </div>
</div>
