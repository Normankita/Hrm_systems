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

                <li class="section-title">
                    PERSONAL
                </li>


                @php
                    $isPageAttendance = Route::is('employee.attendance.dashboard');
                @endphp
                {{-- Leave management ends here --}}
                @canany(['ind_mark_attendance', 'ind_view_attendance'])
                    <li class="has-sub {{ $isPageAttendance ? 'active expand' : '' }}">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                            data-target="#attendance-response-menu" aria-expanded="{{ $isPageAttendance ? 'true' : 'false' }}"
                            aria-controls="attendance-response-menu">
                            <i class="mdi mdi-cash-multiple"></i>
                            <span class="nav-text">Attendance</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="collapse {{ $isPageAttendance ? 'show' : '' }}" id="attendance-response-menu" 
                                data-parent="#sidebar-menu">
                            <div class="sub-menu">
                                <li>
                                    <a class="sidenav-item-link" href="{{ route('employee.attendance.dashboard') }}">
                                        <i class="mdi mdi-eye-outline mr-1"></i>
                                        <span class="nav-text">Dashboard</span>
                                    </a>
                                </li>
                        </ul>
                    </li>
                @endcanany


                @php
                    $isLeavePage = Route::is('employees.leave.request') ||
                        Route::is('employees.leave.status');
                @endphp
                @canany(['request_leave', 'view_leave'])
                    <li class="has-sub {{ $isLeavePage ? 'active expand' : '' }}">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                            data-target="#leave-menu" aria-expanded="{{ $isLeavePage ? 'true' : 'false' }}" 
                                aria-controls="leave-menu">
                            <i class="mdi mdi-calendar"></i>
                            <span class="nav-text">Leave</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="collapse {{ $isLeavePage ? 'show' : '' }}" id="leave-menu" data-parent="#sidebar-menu">
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


                <li class="section-title">
                    MaNAGEMENT
                </li>
                @php
                    $canCreate = auth()->user()->can('create_employees');
                    $canView = auth()->user()->can('view_employees');

                    $isManageEmp = Route::is('employee.manage.employees.create') ||
                        Route::is('employee.manage.employees.index') ||
                        Route::is('employee.manage.employees.reports.index');
                @endphp

                @if ($canCreate || $canView)
                    <li class="has-sub {{ $isManageEmp ? 'active expalnd' : '' }}">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                            data-target="#employee-menu" aria-expanded="{{ $isManageEmp ? 'true' : 'false' }}" 
                                aria-controls="employee-menu">
                            <i class="mdi mdi-account-multiple"></i>
                            <span class="nav-text">Manage Employees</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="collapse {{ $isManageEmp ? 'show' : '' }}" id="employee-menu" data-parent="#sidebar-menu">
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


                @php
                    $isManageAllowance = Route::is('employee.manage.allowances.*') ||
                        Route::is('employee.manage.frequencies.*') ||
                        Route::is('employee.manage.employee.allowances.groups.*') ||
                        Route::is('employee.manage.disbursements.*');
                @endphp
                {{-- Begins Allowances --}}
                @canany(['view_allowances', 'create_allowances'])
                    <li class="has-sub {{ $isManageAllowance ? 'active expand' : '' }}">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                            data-target="#allowance-menu" aria-expanded="{{ $isManageAllowance ? 'true' : 'false' }}" aria-controls="allowance-menu">
                            <i class="mdi mdi-cash-register"></i>
                            <span class="nav-text">Manage allowances</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="collapse {{ $isManageAllowance ? 'show' : '' }}" id="allowance-menu" data-parent="#sidebar-menu">
                            <div class="sub-menu">
                                @can('view_allowances')
                                    <li>
                                        <a class="sidenav-item-link" href="{{ route('employee.manage.allowances.index') }}">
                                            <span class="nav-text">Allowance Categories</span>
                                        </a>
                                    </li>
                                @endcan
                                <li>
                                    <a href="{{ route('employee.manage.employee.allowances.groups.index') }}">
                                        <span class="nav-text">Allowance Groups</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('employee.manage.frequencies.index') }}"> <span
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
                @endcanany


                {{-- Begins loans --}}
                {{-- <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                        data-target="#loan-menu" aria-expanded="false" aria-controls="loan-menu">
                        <i class="mdi mdi-cash-refund"></i>
                        <span class="nav-text">Manage loans</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="loan-menu" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            @can('view_loans')
                                <li>
                                    <a class="sidenav-item-link" href="{{ route('employee.manage.loans.index') }}">
                                        <span class="nav-text">Employee Loans</span>
                                    </a>
                                </li>
                            @endcan
                            @can('create_loans')
                                <li>
                                    <a class="sidenav-item-link" href="{{ route('employee.manage.loans.index') }}">
                                        <span class="nav-text">Loan Employee</span>
                                    </a>
                                </li>
                            @endcan
                        </div>
                    </ul>
                </li> --}}
                {{-- Begins Payroll --}}


                @php
                    $isManageAttendance = Route::is('employee.manage.attendance.*');
                @endphp
                {{-- Attendance Section --}}
                @canany(['view_attendances', 'mark_attendance'])
                    <li class="has-sub {{ $isManageAttendance ? 'active expand' : '' }}">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                            data-target="#loan-menu" aria-expanded="{{ $isManageAttendance ? 'true' : 'false' }}"
                                aria-controls="loan-menu">
                            <i class="mdi mdi-cash-refund"></i>
                            <span class="nav-text">Manage Attendance</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="collapse {{ $isManageAttendance ? 'show' : '' }}" id="loan-menu" 
                                data-parent="#sidebar-menu">
                            <div class="sub-menu">
                                @can('view_attendances')
                                    <li>
                                        <a class="sidenav-item-link"
                                            href="{{ route('employee.manage.attendance.dashboard') }}">
                                            <span class="nav-text">Attendance Dashboard</span>
                                        </a>
                                    </li>
                                @endcan

                                @canany(abilities: ['view_attendances'])
                                    <li>
                                        <a class="sidenav-item-link"
                                            href="{{ route('employee.manage.attendance.dailyAttendance') }}">
                                            <span class="nav-text">Daily Attendance</span>
                                        </a>
                                    </li>
                                @endcan

                                @can(abilities: ['mark_attendance'])
                                    <li>
                                        <a class="sidenav-item-link"
                                            href="{{ route('employee.manage.attendance.manualEntry') }}">
                                            <span class="nav-text">Manual Entry</span>
                                        </a>
                                    </li>
                                @endcan

                                @canany(abilities: ['view_attendances'])
                                    <li>
                                        <a class="sidenav-item-link" href="">
                                            <span class="nav-text">Report & Analysis</span>
                                        </a>
                                    </li>
                                @endcan

                            </div>
                        </ul>
                    </li>
                @endcanany


                @php
                    $isManagePayrolls = Route::is('employee.manage.payrolls.*');
                @endphp
                @canany(['view_payroll', 'create_payroll'])
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


                @php
                    $isManagePayments = Route::is('employee.manage.payroll.employees.*');
                @endphp
                @canany(['view_payment', 'create_payment', 'edit_payment'])
                    <li class="has-sub {{ $isManagePayments ? 'active expand' : '' }}">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                            data-target="#payroll-response-menu" aria-expanded="{{ $isManagePayments ? 'true' : 'false' }}"
                            aria-controls="payroll-response-menu">
                            <i class="mdi mdi-cash-multiple"></i>
                            <span class="nav-text">Manage Payments</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="collapse {{ $isManagePayments ? 'show' : '' }}" id="payroll-response-menu" 
                                data-parent="#sidebar-menu">
                            <div class="sub-menu">
                                <li>
                                    <a class="sidenav-item-link"
                                        href="{{ route('employee.manage.payroll.employees.index') }}">
                                        <i class="mdi mdi-eye-outline mr-1"></i>
                                        <span class="nav-text">View All payrolls</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="sidenav-item-link"
                                        href="{{ route('employee.manage.payroll.employees.pending') }}">
                                        <i class="mdi mdi-clock-outline mr-1"></i>
                                        <span class="nav-text">Pending payrolls</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="sidenav-item-link"
                                        href="{{ route('employee.manage.payroll.employees.approved') }}">
                                        <i class="mdi mdi-check-circle-outline mr-1"></i>
                                        <span class="nav-text">Approved payrolls</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="sidenav-item-link"
                                        href="{{ route('employee.manage.payroll.employees.rejected') }}">
                                        <i class="mdi mdi-close-circle-outline mr-1"></i>
                                        <span class="nav-text">Rejected payrolls</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="sidenav-item-link"
                                        href="{{ route('employee.manage.payroll.employees.reports.index') }}">
                                        <i class="mdi mdi-close-circle-outline mr-1"></i>
                                        <span class="nav-text">Payroll Reports</span>
                                    </a>
                                </li>
                            </div>
                        </ul>
                    </li>
                @endcanany


                @php
                    $isManagePaygrade = Route::is('employee.manage.paygrades.*');
                @endphp
                @can('view_paygrade')
                    <li class="has-sub {{ $isManagePaygrade ? 'active expand' : '' }}">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                            data-target="#paygrade-menu" aria-expanded="{{ $isManagePaygrade ? 'true' : 'false' }}" 
                                aria-controls="employee-menu">
                            <i class="mdi mdi-cash-multiple"></i>
                            <span class="nav-text">Manage PayGrade</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="collapse {{ $isManagePaygrade ? 'show' : '' }}" id="paygrade-menu" data-parent="#sidebar-menu">
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


                @php
                    $isManageLeaveTypes = Route::is('employee.manage.leave.type.*');
                @endphp
                {{-- LeaveType management starts here  --}}
                @can('view_leaveTypes')
                    <li class="has-sub {{ $isManageLeaveTypes ? 'active expand' : '' }}">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                            data-target="#leaveType-menu" aria-expanded="{{ $isManageLeaveTypes ? 'true' : 'false' }}" 
                                aria-controls="leaveType-menu">
                            <i class="mdi mdi-calendar"></i>
                            <span class="nav-text">LeaveType</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="collapse {{ $isManageLeaveTypes ? 'show' : '' }}" id="leaveType-menu" 
                                data-parent="#sidebar-menu">
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


                @php
                    $isPageLeaveManagement = Route::is('employee.manage.leave.index',
                        'employee.manage.leave.reports.reports');
                @endphp
                {{-- LEave management starts here  --}}
                @can('view_leave_requests')
                    <li class="has-sub {{ $isPageLeaveManagement ? 'active expand' : '' }}">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                            data-target="#leaves-menu" aria-expanded="{{ $isPageLeaveManagement ? 'true' : 'false' }}" 
                                aria-controls="leave-menu">
                            <i class="mdi mdi-calendar"></i>
                            <span class="nav-text">Leaves Management</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="collapse {{ $isPageLeaveManagement ? 'show' : '' }}" id="leaves-menu" 
                                data-parent="#sidebar-menu">
                            <div class="sub-menu">
                                <li>
                                    <a class="sidenav-item-link" href="{{ route('employee.manage.leave.index') }}">
                                        <span class="nav-text">Manage Leaves</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="sidenav-item-link"
                                        href="{{ route('employee.manage.leave.reports.reports') }}">
                                        <span class="nav-text">Leave Reports</span>
                                    </a>
                                </li>
                            </div>
                        </ul>
                    </li>
                @endcan
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
