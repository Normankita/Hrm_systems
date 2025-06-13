@php
    use App\Http\Services\DashboardDataService;
    $invalidState = false;
@endphp

@extends('layouts.system')

@section('_links')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection

@section('content')
    @role('ADMIN')
        @include('admin.dashboard')
    @endrole

    @role('EMPLOYEE')
        @php
            $employee = Auth::user()->employee;
            $dashboard = collect(DashboardDataService::getEmployeeDashboardData($employee));
        @endphp
        @if ($employee && $dashboard->isNotEmpty())
            @include('employee.dashboard')
        @else
            @php
                $invalidState = true;
            @endphp
        @endif
    @endrole

    @role('HR_OFFICER')
        @include('hr.dashboard')
    @endrole

    @role('PAYROLL_MANAGER')
        @include('payroll.dashboard')
    @endrole
    @if ($invalidState)
        <div class="flex items-center justify-center h-screen">
            <div class="bg-white shadow-md rounded-lg p-6 text-center">
                <h1 class="text-2xl font-bold text-red-600 mb-4">Invalid State</h1>
                <p class="text-gray-700">The employee data is not available or the user is not assigned to an employee.</p>
            </div>
        </div>
    @endif
@endsection
