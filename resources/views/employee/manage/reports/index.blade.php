@extends('layouts.system')

@section('content')
    <div class="card shadow rounded">
        <div class="card-header bg-primary">
            <h4 class="mb-0 text-white">Reports</h4>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @can('view_employee_reports')
                    <div class="col-md-4">
                        <x-system.reports.report-card-anchor title="Employees Report" :route="route('employee.manage.reports.employees')" />
                    </div>
                @endcan

                @can('view_attendance_reports')
                    <div class="col-md-4">
                        <x-system.reports.report-card-anchor title="Attendance Report" :route="route('employee.manage.reports.attendance')" />
                    </div>
                @endcan

                @can('view_payroll_reports')
                    <div class="col-md-4">
                        <x-system.reports.report-card-anchor title="Payroll Report" :route="route('employee.manage.reports.payroll')" />
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection

