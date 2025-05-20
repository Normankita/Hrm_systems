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

                <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#employee-menu" aria-expanded="false" aria-controls="employee-menu">
                        <i class="mdi mdi-cash-register"></i>
                        <span class="nav-text">Payrolls</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="employee-menu" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link" href="{{ route('payroll.employees.index') }}">
                                    <span class="nav-text">View Employees</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('payrolls.index') }}">
                                    <span class="nav-text">View Payrolls</span>
                                </a>
                            </li>
                        </div>

                    </ul>
                </li>
                <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#paygrade-menu" aria-expanded="false" aria-controls="employee-menu">
                        <i class="mdi mdi-cash-multiple"></i>
                        <span class="nav-text">PayGrade</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="paygrade-menu" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link" href="{{ route('payroll.paygrades.index') }}">
                                    <span class="nav-text">View PayGrades</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>

                {{-- LEave management starts here  --}}

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
                                <a class="sidenav-item-link" href="{{ route('employee.manage.leave.index') }}">
                                    <span class="nav-text">Manage Leaves</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>

                {{-- Leave management ends here --}}


                <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#leave-menu" aria-expanded="false" aria-controls="leave-menu">
                        <i class="mdi mdi-calendar"></i>
                        <span class="nav-text">Leave</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="leave-menu" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link" href="{{ route('employees.leave.request') }}">
                                    <span class="nav-text">Request Leave</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('employees.leave.status') }}">
                                    <span class="nav-text">View Leaves</span>
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
