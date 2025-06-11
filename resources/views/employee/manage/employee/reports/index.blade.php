@extends('layouts.system')
@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-header bg-primary">
                <h4 class="mb-0 text-white">Employees Reports</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <x-system.reports.report-card-anchor title="Suspended Employees" :route="route('employee.manage.employees.reports.suspended')">
                        </x-system.reports.report-card-anchor>
                    </div>

                    <div class="col-md-4">
                        <x-system.reports.report-card-anchor title="Active Employees" :route="route('employee.manage.employees.reports.active')">
                        </x-system.reports.report-card-anchor>
                    </div>
                </div>
            </div>
        </div>
    @endsection
