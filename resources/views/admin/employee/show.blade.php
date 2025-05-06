@extends('layouts.system')

@section('content')
<x-system.displays.employee-profile
:employee="$employee"
:backLink="route('admin.employees.index')"
:editLink="route('admin.employees.edit', $employee->id)"
:updatePhotoRoute="route('admin.employees.updateProfilePhoto', $employee->id)"
/>

@endsection