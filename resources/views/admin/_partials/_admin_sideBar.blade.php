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

                <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#employee-menu" aria-expanded="false" aria-controls="employee-menu">
                        <i class="mdi mdi-account-multiple"></i>
                        <span class="nav-text">Employees</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="employee-menu" data-parent="#sidebar-menu">
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

                <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#leaves-menu" aria-expanded="false" aria-controls="leave-menu">
                        <i class="mdi mdi-calendar"></i>
                        <span class="nav-text">Leaves</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="leaves-menu" data-parent="#sidebar-menu">
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

                <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#leaveType-menu" aria-expanded="false" aria-controls="leaveType-menu">
                        <i class="mdi mdi-calendar"></i>
                        <span class="nav-text">LeaveType</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="leaveType-menu" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link" href="{{ route('admin.leave.type.index') }}">
                                    <span class="nav-text">Manage LeaveTypes</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>

                <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#allowance-menu" aria-expanded="false" aria-controls="allowance-menu">
                        <i class="mdi mdi-cash-register"></i>
                        <span class="nav-text">Allowances</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="allowance-menu" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            @can('view_allowances')
                                <li>
                                    {{-- <a class="sidenav-item-link" href="{{ route('admin.manage.allowances.index') }}">
                                        <span class="nav-text">Allowance Categories</span>
                                    </a> --}}
                                </li>
                            @endcan
                                                <li>
                                    <a href="{{ route('admin.employee.allowances.groups.index') }}">
                                        <span class="nav-text">Allowance Groups</span>
                                    </a>
                                </li>
                            <li>
                                <a href="{{ route('admin.frequencies.index') }}"> <span
                                        class="nav-text">Manage Frequencies</span>
                                </a>
                            </li>
                            {{-- <li>
                                    <a href="{{ route('employee.manage.disbursements.index') }}"> <span
                                            class="nav-text">Disbursements</span>
                                    </a>
                                </li> --}}
                        </div>

                    </ul>
                </li>

                <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#attendance-menu" aria-expanded="false" aria-controls="attendance-menu">
                        <i class="mdi mdi-cash-refund"></i>
                        <span class="nav-text">Attendance</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="attendance-menu" data-parent="#sidebar-menu">
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
                            <li class="has-sub">
                                <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                    data-target="#session-menu" aria-expanded="false" aria-controls="session-menu">
                                    <span class="nav-text">Session / Shifts</span>
                                    <b class="caret"></b>
                                </a>
                                <ul class="collapse" id="session-menu" data-parent="#attendance-menu">
                                    <div class="sub-menu">
                                        <li>
                                            <a class="sidenav-item-link"
                                                href="{{ route('admin.attendances.sessions.index') }}">
                                                <span class="nav-text">Manage Session</span>
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

                <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#roles_menu" aria-expanded="false" aria-controls="roles_menu">
                        <i class="mdi mdi-account-multiple"></i>
                        <span class="nav-text">Roles</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="roles_menu" data-parent="#sidebar-menu">
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

                <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#department_menu" aria-expanded="false" aria-controls="department_menu">
                        <i class="mdi mdi-sitemap"></i>
                        <span class="nav-text">Departments</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="department_menu" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link" href="{{ route('admin.departments.index') }}">
                                    <span class="nav-text">view departments</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- Bottom Section -->
        <div class="sidebar-footer" style="position: absolute; bottom: 0; width: 100%; padding: 10px;">
            <ul class="nav">
                <li>
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
