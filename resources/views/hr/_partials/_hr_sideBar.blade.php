<aside class="left-sidebar sidebar-dark" id="left-sidebar">
    <div id="sidebar" class="sidebar sidebar-with-footer">
        <!-- Application Brand -->
        <div class="app-brand">
            <a href="/dashboard">
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
                                    <span class="nav-text">Leave Status</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>

                <li>
                    <a class="sidenav-item-link" href="{{ route('employees.profile.index') }}">
                        <i class="mdi mdi-account-circle"></i>
                        <span class="nav-text">My Profile</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
