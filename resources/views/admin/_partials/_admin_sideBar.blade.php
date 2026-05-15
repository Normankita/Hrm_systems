<aside class="left-sidebar sidebar-dark" id="left-sidebar">
    <div id="sidebar" class="sidebar sidebar-with-footer">
        <!-- Application Brand -->
        <div class="app-brand">
            <a href="{{ route('dashboard') }}">
                <span class="brand-name">HRMS</span>
            </a>
        </div>

        <!-- Sidebar Content -->
        <div class="sidebar-left" data-simplebar style="height: 100%;">
            <ul class="nav sidebar-inner" id="sidebar-menu">

                <li>
                    <a class="sidenav-item-link" href="{{ route('dashboard') }}">
                        <i class="mdi mdi-briefcase-account-outline"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>


                @php
                    $isPageEmployee = Route::is(
                        'admin.employees.create',
                        'admin.employees.index',
                        'admin.employees.permissions.all',
                    );
                @endphp
                <li class="has-sub {{ $isPageEmployee ? 'active expand' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#employee-menu" aria-expanded="{{ $isPageEmployee ? 'true' : 'false' }}"
                        aria-controls="employee-menu">
                        <i class="mdi mdi-account-multiple"></i>
                        <span class="nav-text">Employees</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse {{ $isPageEmployee ? 'show' : '' }}" id="employee-menu"
                        data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link" href="{{ route('admin.employees.create') }}">
                                    <span class="nav-text">Create Employee</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('admin.employees.index') }}">
                                    <span class="nav-text">View Employees</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('admin.employees.permissions.all') }}">
                                    <span class="nav-text">Employee permissions</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>


                @php
                    $isPageLeave = Route::is('admin.leave.index', 'admin.leave.reports.reports');
                @endphp
                <li class="has-sub {{ $isPageLeave ? 'active expand' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#leaves-menu" aria-expanded="{{ $isPageLeave ? 'true' : 'false' }}"
                        aria-controls="leave-menu">
                        <i class="mdi mdi-calendar"></i>
                        <span class="nav-text">Leaves</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse {{ $isPageLeave ? 'show' : '' }}" id="leaves-menu" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link" href="{{ route('admin.leave.index') }}">
                                    <span class="nav-text">Manage Leaves</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('admin.leave.reports.reports') }}">
                                    <span class="nav-text">Leave Reports</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>


                @php
                    $isPageLeaveType = Route::is('admin.leave.type.index');
                @endphp
                <li class="has-sub {{ $isPageLeaveType ? 'active expand' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#leaveType-menu" aria-expanded="{{ $isPageLeaveType ? 'true' : 'false' }}"
                        aria-controls="leaveType-menu">
                        <i class="mdi mdi-calendar"></i>
                        <span class="nav-text">LeaveType</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse {{ $isPageLeaveType ? 'show' : '' }}" id="leaveType-menu"
                        data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link" href="{{ route('admin.leave.type.index') }}">
                                    <span class="nav-text">Manage LeaveTypes</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>


                @php
                    $isPageAllowance = Route::is(
                        'admin.allowances.index',
                        'admin.employee.allowances.groups.index',
                        'admin.frequencies.index',
                        'admin.disbursements.index',
                    );
                @endphp
                <li class="has-sub {{ $isPageAllowance ? 'active expand' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#allowance-menu" aria-expanded="{{ $isPageAllowance ? 'true' : 'false' }}"
                        aria-controls="allowance-menu">
                        <i class="mdi mdi-cash-register"></i>
                        <span class="nav-text">Allowances</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse {{ $isPageAllowance ? 'show' : '' }}" id="allowance-menu"
                        data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            @can('view_allowances')
                                <li>
                                    <a class="sidenav-item-link" href="{{ route('admin.allowances.index') }}">
                                        <span class="nav-text">Allowance Categories</span>
                                    </a>
                                </li>
                            @endcan
                            <li>
                                <a href="{{ route('admin.frequencies.index') }}"> <span class="nav-text">Manage
                                        Frequencies</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.employee.allowances.groups.index') }}">
                                    <span class="nav-text">Allowance Groups</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.disbursements.index') }}"> <span
                                        class="nav-text">Disbursements</span>
                                </a>
                            </li>
                        </div>

                    </ul>
                </li>


                @php
                    $isPageAttendance = Route::is(
                        'admin.attendances.index',
                        'admin.attendances.daily.page',
                        'admin.attendances.manual.entry.page',
                        'admin.attendances.sessions.index',
                        'admin.attendances.sessions.get.dashboard',
                    );
                @endphp
                <li class="has-sub {{ $isPageAttendance ? 'active expand' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#attendance-menu" aria-expanded="{{ $isPageAttendance ? 'true' : 'false' }}"
                        aria-controls="attendance-menu">
                        <i class="mdi mdi-cash-refund"></i>
                        <span class="nav-text">Attendance</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse {{ $isPageAttendance ? 'show' : '' }}" id="attendance-menu"
                        data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <!-- Other Attendance Links -->
                            <li>
                                <a class="sidenav-item-link" href="{{ route('admin.attendances.index') }}">
                                    <span class="nav-text">Attendance Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('admin.attendances.daily.page') }}">
                                    <span class="nav-text">Daily Attendance</span>
                                </a>
                            </li>

                            <li>
                                <a class="sidenav-item-link"
                                    href="{{ route('admin.attendances.manual.entry.page') }}">
                                    <span class="nav-text">Manual Entry</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="">
                                    <span class="nav-text">Report & Analysis</span>
                                </a>
                            </li>
                            <!-- Session Sublist -->
                            @php
                                $isPageSession = Route::is(
                                    'admin.attendances.sessions.index',
                                    'admin.attendances.sessions.get.dashboard',
                                );
                            @endphp
                            <li class="has-sub {{ $isPageSession ? 'active expand' : '' }}">
                                <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                    data-target="#session-menu"
                                    aria-expanded="{{ $isPageSession ? 'true' : 'false' }}"
                                    aria-controls="session-menu">
                                    <span class="nav-text">Session / Shifts</span>
                                    <b class="caret"></b>
                                </a>
                                <ul class="collapse {{ $isPageSession ? 'show' : '' }}" id="session-menu"
                                    data-parent="#attendance-menu">
                                    <div class="sub-menu">
                                        <li>
                                            <a class="sidenav-item-link"
                                                href="{{ route('admin.attendances.sessions.index') }}">
                                                <span class="nav-text">Manage Session</span>
                                            </a>
                                        </li>
                                          <li>
                                            <a class="sidenav-item-link"
                                                href="{{ route('admin.attendances.sessions.get.employees.shift') }}">
                                                <span class="nav-text">Employee Shift</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="sidenav-item-link"
                                                href="{{ route('admin.attendances.sessions.get.dashboard') }}">
                                                <span class="nav-text">Session Attendance</span>
                                            </a>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </div>
                    </ul>
                </li>


                @php
                    $isPageRole = Route::is('admin.roles.index');
                @endphp
                <li class="has-sub {{ $isPageRole ? 'active expand' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#roles_menu" aria-expanded="{{ $isPageRole ? 'true' : 'false' }}"
                        aria-controls="roles_menu">
                        <i class="mdi mdi-account-multiple"></i>
                        <span class="nav-text">Roles</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse {{ $isPageRole ? 'show' : '' }}" id="roles_menu"
                        data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link"
                                    href="
                                    {{ route('admin.roles.index') }}">
                                    <span class="nav-text">Manage roles</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>

                @php
                    $isPagePaygrade = Route::is('admin.paygrades.*');
                @endphp
                <li class="has-sub {{ $isPagePaygrade ? 'active expand' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#paygrade-menu" aria-expanded="{{ $isPagePaygrade ? 'true' : 'false' }}"
                        aria-controls="employee-menu">
                        <i class="mdi mdi-cash-multiple"></i>
                        <span class="nav-text">PayGrade</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse {{ $isPagePaygrade ? 'show' : '' }}" id="paygrade-menu"
                        data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link" href="{{ route('admin.paygrades.index') }}">
                                    <span class="nav-text">View PayGrades</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>


                @php
                    $isManagePayrolls = Route::is('admin.payrolls.*');
                @endphp
                <li class="has-sub {{ $isManagePayrolls ? 'active expland' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#payroll-menu" aria-expanded="{{ $isManagePayrolls ? 'true' : 'false' }}"
                        aria-controls="payroll-menu">
                        <i class="mdi mdi-cash-register"></i>
                        <span class="nav-text">Manage Payrolls</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse {{ $isManagePayrolls ? 'show' : '' }}" id="payroll-menu"
                        data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link"
                                href="{{ route('admin.payrolls.getEmployees') }}">
                                    <span class="nav-text">Select Employees</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('admin.payrolls.index') }}">
                                    <span class="nav-text">View Payrolls</span>
                                </a>
                            </li>
                        </div>

                    </ul>
                </li>


                @php
                    $isManagePayments = Route::is('admin.payroll.employees.*');
                @endphp
                <li class="has-sub {{ $isManagePayments ? 'active expand' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#payroll-response-menu"
                        aria-expanded="{{ $isManagePayments ? 'true' : 'false' }}"
                        aria-controls="payroll-response-menu">
                        <i class="mdi mdi-cash-multiple"></i>
                        <span class="nav-text">Manage Payments</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse {{ $isManagePayments ? 'show' : '' }}" id="payroll-response-menu"
                        data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link" href="{{ route('admin.payroll.employees.index') }}">
                                    <i class="mdi mdi-eye-outline mr-1"></i>
                                    <span class="nav-text">View All payrolls</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('admin.payroll.employees.pending') }}">
                                    <i class="mdi mdi-clock-outline mr-1"></i>
                                    <span class="nav-text">Pending payrolls</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('admin.payroll.employees.approved') }}">
                                    <i class="mdi mdi-check-circle-outline mr-1"></i>
                                    <span class="nav-text">Approved payrolls</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('admin.payroll.employees.rejected') }}">
                                    <i class="mdi mdi-close-circle-outline mr-1"></i>
                                    <span class="nav-text">Rejected payrolls</span>
                                </a>
                            </li>
                            {{-- <li>
                                <a class="sidenav-item-link"
                                    href="{{ route('admin.payroll.employees.reports.index') }}">
                                    <i class="mdi mdi-close-circle-outline mr-1"></i>
                                    <span class="nav-text">Payroll Reports</span>
                                </a>
                            </li> --}}
                        </div>
                    </ul>
                </li>


                @php
                    $isPageDepartment = Route::is('admin.departments.index');
                @endphp
                <li class="has-sub {{ $isPageDepartment ? 'active expand' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#department_menu" aria-expanded="{{ $isPageDepartment ? 'true' : 'false' }}"
                        aria-controls="department_menu">
                        <i class="mdi mdi-sitemap"></i>
                        <span class="nav-text">Departments</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse {{ $isPageDepartment ? 'show' : '' }}" id="department_menu"
                        data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link" href="{{ route('admin.departments.index') }}">
                                    <span class="nav-text">view departments</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>

                @php
                    $isPageReports = Route::is('admin.reports.*');
                @endphp
                @canany([
                    'view_reports',
                    'view_employee_reports',
                    'view_attendance_reports',
                    'view_leave_reports',
                    'view_payroll_reports',
                    'view_allowance_reports',
                    'view_loan_reports',
                    'view_deduction_reports',
                    'view_disbursement_reports',
                ])
                    <li class="has-sub {{ $isPageReports ? 'active expand' : '' }}">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                            data-target="#reports-menu" aria-expanded="{{ $isPageReports ? 'true' : 'false' }}"
                            aria-controls="reports-menu">
                            <i class="mdi mdi-file-chart"></i>
                            <span class="nav-text">Reports</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="collapse {{ $isPageReports ? 'show' : '' }}" id="reports-menu"
                            data-parent="#sidebar-menu">
                            <div class="sub-menu">
                                @can('view_reports')
                                    <li>
                                        <a class="sidenav-item-link" href="{{ route('admin.reports.index') }}">
                                            <span class="nav-text">Reports Dashboard</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_employee_reports')
                                    <li>
                                        <a class="sidenav-item-link" href="{{ route('admin.reports.employees') }}">
                                            <span class="nav-text">Employees</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_attendance_reports')
                                    <li>
                                        <a class="sidenav-item-link" href="{{ route('admin.reports.attendance') }}">
                                            <span class="nav-text">Attendance</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_payroll_reports')
                                    <li>
                                        <a class="sidenav-item-link" href="{{ route('admin.reports.payroll') }}">
                                            <span class="nav-text">Payroll</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_leave_reports')
                                    <li>
                                        <a class="sidenav-item-link" href="{{ route('admin.leave.reports.reports') }}">
                                            <span class="nav-text">Leaves (legacy)</span>
                                        </a>
                                    </li>
                                @endcan
                            </div>
                        </ul>
                    </li>
                @endcanany

                <li class="mt-3">
                    <a class="sidenav-item-link" style="color: white; padding: 10px 15px;"
                        href="{{ route('admin.companies.edit', auth()->user()->company_id) }}">
                        <i class="mdi mdi-account-circle"></i>
                        <span class="nav-text">Company Profile</span>
                    </a>
                </li>
                <li>
                    <a class="sidenav-item-link" style="color: white; padding: 10px 15px;"
                        href="{{ route('admin.settings.index', auth()->user()->company_id) }}">
                        <i class="mdi mdi-settings"></i>
                        <span class="nav-text">Company Settins</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
