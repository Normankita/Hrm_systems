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
                        <i class="mdi mdi-view-dashboard"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                @php
                    $canCreate = auth()->user()->can('create_employees');
                    $canView = auth()->user()->can('view_employees');
                @endphp

                @if ($canCreate || $canView)
                    <li class="has-sub">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                            data-target="#employee-menu" aria-expanded="false" aria-controls="employee-menu">
                            <i class="mdi mdi-account-multiple"></i>
                            <span class="nav-text">Employees</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="collapse" id="employee-menu" data-parent="#sidebar-menu">
                            <div class="sub-menu">
                                @if ($canCreate)
                                    <li>
                                        <a class="sidenav-item-link"
                                            href="{{ route('employee.manage.employees.create') }}">
                                            <span class="nav-text">Create Employee</span>
                                        </a>
                                    </li>
                                @endif

                                @if ($canView)
                                    <li>
                                        <a class="sidenav-item-link"
                                            href="{{ route('employee.manage.employees.index') }}">
                                            <span class="nav-text">View Employees</span>
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a class="sidenav-item-link"
                                        href="{{ route('employee.manage.employees.reports.index') }}">
                                        <span class="nav-text">Employee Reports</span>
                                    </a>
                                </li>
                            </div>
                        </ul>
                    </li>
                @endif

                {{-- Begins Allowances --}}
                @canany(['view_allowances', 'create_allowances'])
                    <li class="has-sub">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                            data-target="#allowance-menu" aria-expanded="false" aria-controls="allowance-menu">
                            <i class="mdi mdi-cash-register"></i>
                            <span class="nav-text">Manage allowances</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="collapse" id="allowance-menu" data-parent="#sidebar-menu">
                            <div class="sub-menu">
                                @can('create_allowances')
                                   <li>
                                        <a class="sidenav-item-link" href="{{ route('employee.manage.allowances.create') }}">
                                            <span class="nav-text">Create allowances</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_allowances')
                                    <li>
                                        <a class="sidenav-item-link" href="{{ route('employee.manage.allowances.index') }}">
                                            <span class="nav-text">View allowances</span>
                                        </a>
                                    </li>
                                @endcan
                            </div>

                        </ul>
                    </li>
                @endcanany

                {{-- Begins Payroll --}}
                @canany(['view_payroll', 'create_payroll'])
                    <li class="has-sub">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                            data-target="#payroll-menu" aria-expanded="false" aria-controls="payroll-menu">
                            <i class="mdi mdi-cash-register"></i>
                            <span class="nav-text">Manage Payrolls</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="collapse" id="payroll-menu" data-parent="#sidebar-menu">
                            <div class="sub-menu">
                                @can('create_payroll')
                                    <li>
                                        <a class="sidenav-item-link"
                                            href="{{ route('employee.manage.payrolls.getEmployees') }}">
                                            <span class="nav-text">Select Employees</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_payroll')
                                    <li>
                                        <a class="sidenav-item-link" href="{{ route('employee.manage.payrolls.index') }}">
                                            <span class="nav-text">View Payrolls</span>
                                        </a>
                                    </li>
                                @endcan
                            </div>

                        </ul>
                    </li>
                @endcanany

                @canany(['view_payment', 'create_payment', 'edit_payment'])
                    <li class="has-sub">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                            data-target="#payroll-response-menu" aria-expanded="false" aria-controls="payroll-response-menu">
                            <i class="mdi mdi-cash-multiple"></i>
                            <span class="nav-text">Manage Payments</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="collapse" id="payroll-response-menu" data-parent="#sidebar-menu">
                            <div class="sub-menu">
                                <li>
                                    <a class="sidenav-item-link" href="{{route('employee.manage.payroll.employees.index')}}">
                                        <i class="mdi mdi-eye-outline mr-1"></i>
                                        <span class="nav-text">View All payrolls</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="sidenav-item-link" href="{{route('employee.manage.payroll.employees.pending')}}">
                                        <i class="mdi mdi-clock-outline mr-1"></i>
                                        <span class="nav-text">Pending payrolls</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="sidenav-item-link" href="{{route('employee.manage.payroll.employees.approved')}}">
                                        <i class="mdi mdi-check-circle-outline mr-1"></i>
                                        <span class="nav-text">Approved payrolls</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="sidenav-item-link" href="{{route('employee.manage.payroll.employees.rejected')}}">
                                        <i class="mdi mdi-close-circle-outline mr-1"></i>
                                        <span class="nav-text">Rejected payrolls</span>
                                    </a>
                                </li>
                            </div>
                        </ul>
                    </li>
                @endcanany
                @can('view_paygrade')
                    <li class="has-sub">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                            data-target="#paygrade-menu" aria-expanded="false" aria-controls="employee-menu">
                            <i class="mdi mdi-cash-multiple"></i>
                            <span class="nav-text">Manage PayGrade</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="collapse" id="paygrade-menu" data-parent="#sidebar-menu">
                            <div class="sub-menu">
                                <li>
                                    <a class="sidenav-item-link" href="{{ route('employee.manage.paygrades.index') }}">
                                        <span class="nav-text">View PayGrades</span>
                                    </a>
                                </li>
                            </div>
                        </ul>
                    </li>
                @endcan

                                {{-- LeaveType management starts here  --}}

                @can('view_leaveTypes')
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
                                    <a class="sidenav-item-link" href="{{ route('employee.manage.leave.type.index') }}">
                                        <span class="nav-text">Manage LeaveTypes</span>
                                    </a>
                                </li>
                            </div>
                        </ul>
                    </li>
                @endcan

                {{-- LEave management starts here  --}}

                @can('view_leave_requests')
                    <li class="has-sub">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                            data-target="#leaves-menu" aria-expanded="false" aria-controls="leave-menu">
                            <i class="mdi mdi-calendar"></i>
                            <span class="nav-text">Leaves Management</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="collapse" id="leaves-menu" data-parent="#sidebar-menu">
                            <div class="sub-menu">
                                <li>
                                    <a class="sidenav-item-link" href="{{ route('employee.manage.leave.index') }}">
                                        <span class="nav-text">Manage Leaves</span>
                                    </a>
                                </li>
                                 <li>
                                    <a class="sidenav-item-link" href="{{ route('employee.manage.leave.reports.reports') }}">
                                        <span class="nav-text">Leave Reports</span>
                                    </a>
                                </li>
                            </div>
                        </ul>
                    </li>
                @endcan

                {{-- Leave management ends here --}}

                @canany(['request_leave', 'view_leave'])
                    <li class="has-sub">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                            data-target="#leave-menu" aria-expanded="false" aria-controls="leave-menu">
                            <i class="mdi mdi-calendar"></i>
                            <span class="nav-text">Leave</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="collapse" id="leave-menu" data-parent="#sidebar-menu">
                            <div class="sub-menu">
                                @can('request_leave')
                                    <li>
                                        <a class="sidenav-item-link" href="{{ route('employees.leave.request') }}">
                                            <span class="nav-text">Request Leave</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_leave')
                                    <li>
                                        <a class="sidenav-item-link" href="{{ route('employees.leave.status') }}">
                                            <span class="nav-text">View Leaves</span>
                                        </a>
                                    </li>
                                @endcan
                            </div>
                        </ul>
                    </li>
                @endcanany
            </ul>
        </div>

        <!-- Bottom Section -->
        <div class="sidebar-footer" style="position: absolute; bottom: 0; width: 100%; padding: 10px;">
            <ul class="nav">
                <li>
                    <a class="sidenav-item-link" href="{{ route('employees.profile.index') }}"
                        style="color: white; padding: 10px 15px;">
                        <i class="mdi mdi-account-circle"></i>
                        <span class="nav-text">My Profile</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
