<aside class="left-sidebar sidebar-dark" id="left-sidebar">
    <div id="sidebar" class="sidebar sidebar-with-footer">
        <!-- Application Brand -->
        <div class="app-brand">
            <a href="{{route('dashboard')}}">
                <span class="brand-name">HRMS</span>
            </a>
        </div>

        <!-- Sidebar Content -->
        <div class="sidebar-left" data-simplebar style="height: 100%;">
            <ul class="nav sidebar-inner" id="sidebar-menu">

                <li>
                    <a class="sidenav-item-link" href="{{route('dashboard')}}">
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
                                {{-- <a class="sidenav-item-link" href="{{ route('admin.employees.index') }}">
                                    <span class="nav-text">View Employees</span>
                                </a> --}}
                                <a class="sidenav-item-link" href="{{route('admin.employees.index')}}">
                                    <span class="nav-text">View Employees</span>
                                </a>
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
                                <a class="sidenav-item-link" href="
                                    {{ route('admin.roles.index') }}">
                                    <span class="nav-text">Create Role</span>
                                </a>
                            </li>
                            <li>
                                {{-- <a class="sidenav-item-link" href="{{ route('admin.employees.index') }}">
                                    <span class="nav-text">View Employees</span>
                                </a> --}}
                                <a class="sidenav-item-link"
                                    href="{{route('admin.employees.index')}}">
                                    <span class="nav-text">Assign Role</span>
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
                <li>
                    <a class="sidenav-item-link" href="{{ route('admin.companies.edit', auth()->user()->company_id) }}">
                        <i class="mdi mdi-settings"></i>
                        <span class="nav-text">Company Profile</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
