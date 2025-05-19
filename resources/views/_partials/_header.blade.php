@role('ADMIN')
    @include('admin._partials._admin_header')
@endrole

@role('EMPLOYEE')
    @include('employee._partials._employee_header')
@endrole

@role('HR_OFFICER')
    @include('hr._partials._hr_header')
@endrole


@role('PAYROLL_MANAGER')
    @include('payroll._partials._payroll_header')
@endrole
