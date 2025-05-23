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
                        data-target="#leave-menu" aria-expanded="false" aria-controls="leave-menu">
                        <i class="mdi mdi-calendar"></i>
                        <span class="nav-text">Leaves</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="leave-menu" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link" href="{{ route('hr.leave.index') }}">
                                    <span class="nav-text">Manage Leaves</span>
                                </a>
                            </li>
                        </div>
                    </ul>
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
                                <a class="sidenav-item-link" href="{{ route('hr.employees.create') }}">
                                    <span class="nav-text">Create Employee</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('hr.employees.index') }}">
                                    <span class="nav-text">View Employees</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>
                <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#payrolls-menu" aria-expanded="false" aria-controls="payrolls-menu">
                        <i class="mdi mdi-cash-multiple"></i>
                        <span class="nav-text">Payrolls</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="payrolls-menu" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li>
                                <a class="sidenav-item-link" href="{{ route('hr.payrolls.index') }}">
                                    <i class="mdi mdi-eye-outline mr-1"></i>
                                    <span class="nav-text">View All payrolls</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('hr.payrolls.pending') }}">
                                    <i class="mdi mdi-clock-outline mr-1"></i>
                                    <span class="nav-text">Pending payrolls</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('hr.payrolls.approved') }}">
                                    <i class="mdi mdi-check-circle-outline mr-1"></i>
                                    <span class="nav-text">Approved payrolls</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{ route('hr.payrolls.rejected') }}">
                                    <i class="mdi mdi-close-circle-outline mr-1"></i>
                                    <span class="nav-text">Rejected payrolls</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>

            </ul>
        </div>
        <!-- Bottom Section -->
        <div class="sidebar-footer" style="position: absolute; bottom: 0; width: 100%; padding: 15px;">
            <ul class="nav">
                <li>
                    <a class="sidenav-item-link" href="#" style="color: white; padding: 10px 15px;">
                        <i class="mdi mdi-account-circle"></i>
                        <span class="nav-text">My Profile</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
