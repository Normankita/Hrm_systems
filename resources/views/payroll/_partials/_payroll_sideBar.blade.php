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
                    <a class="sidenav-item-link" href="javascript:void(0)"
                        data-toggle="collapse"
                        data-target="#employee-menu" aria-expanded="false"
                            aria-controls="employee-menu">
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
                                <a class="sidenav-item-link" href="{{route('hr.employees.index')}}">
                                    <span class="nav-text">View Employees</span>
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
