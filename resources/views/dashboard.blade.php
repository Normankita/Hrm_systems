@extends('layouts.system')

@section('_links')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection

@section('content')
    @role('ADMIN')
        @include('admin.dashboard')
    @endrole

    @role('EMPLOYEE')
    {{-- @if (Auth::user()->created_at===Auth::user()->updated_at)
        <h1>Hello I am new</h1>
    @endif --}}
        @include('employee.dashboard')
    @endrole

    @role('HR_OFFICER')
        @include('hr.dashboard')
    @endrole
@endsection

