@extends('layouts.system')

@section('content')
    <!-- a table to show rejected employee -->
        <div class="card shadow rounded">
            <div class="card-header bg-success">
                <h4 class="mb-0 text-white">Active employees</h4>
            </div>
            <div class="card-body">
                <x-system.reports.generic-report id="activeEmployee">
                    <x-slot name="viewTable">
                        <x-system.tables.reports.employees.employees :employees="$employees" class="dt-table" />
                    </x-slot>
                    <x-slot name="reportTable">
                        <x-system.tables.reports.employees.employees :employees="$employees" id="activeEmployee">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="text-muted">
                                        Note: The table below shows all Active Employees.
                                    </p>
                                </div>
                            </div>
                        </x-system.tables.reports.employees.employees>
                    </x-slot>
                </x-system.reports.generic-report>
            </div>
        </div>
    @endsection
