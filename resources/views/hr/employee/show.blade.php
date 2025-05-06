@extends('layouts.system')

@section('content')

<x-system.displays.employee-profile
:employee="$employee"
:backLink="route('hr.employees.index')"
:editLink="route('hr.employees.edit', $employee->id)"
:updatePhotoRoute="route('hr.employees.updateProfilePhoto', $employee->id)"
/>


@endsection