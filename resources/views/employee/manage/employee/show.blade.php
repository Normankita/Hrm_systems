@extends('layouts.system')

@section('content')
    @can('view_employees')
        <x-system.displays.employee-profile :employee="$employee" prefix="employee.manage.employees" :attachments="$attachments" />
    @endcan
@endsection
