@extends('layouts.system')

@section('_links')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection

@section('content')
    @role('ADMIN')
        @include('admin.dashboard')
        
    @endrole

    @role('EMPLOYEE')
        @include('employee.dashboard')
     
    @endrole

    @role('HR')
        @include('hr.dashboard')
    @endrole
@endsection

