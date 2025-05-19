@extends('layouts.system')

@section('content')
    <x-system.displays.employee-profile :employee="$employee" prefix="payroll.employees" :attachments="$attachments" :pay_grades="$pay_grades" />


@endsection
