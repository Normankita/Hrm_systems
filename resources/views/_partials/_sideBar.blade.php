@role('ADMIN')
    @include('admin._partials._admin_sideBar')
@endrole

@role('EMPLOYEE')
    @include('employee._partials._employee_sideBar')
@endrole

@role('HR_OFFICER')
    @include('hr._partials._hr_sideBar')
@endrole


@role('PAYROLL_MANAGER')
    @include('payroll._partials._payroll_sideBar')
@endrole
