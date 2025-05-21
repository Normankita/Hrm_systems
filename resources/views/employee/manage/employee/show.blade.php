@extends('layouts.system')

@section('content')

<x-system.displays.employee-profile
:employee="$employee"
prefix="employee.manage.employees"
:attachments="$attachments"

/>


@endsection