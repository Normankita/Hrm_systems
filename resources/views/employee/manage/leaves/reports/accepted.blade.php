@extends('layouts.system')

@section('content')
    <!-- a table to show rejected leaves -->
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-header bg-success">
                <h4 class="mb-0 text-white">Accepted Leaves</h4>
            </div>
            <div class="card-body">
                <x-system.reports.generic-report id="acceptedLeaves">
                    <x-slot name="viewTable">
                        <x-system.tables.reports.leaves.leaves :leaves="$acceptedLeaves" class="dt-table" />
                    </x-slot>
                    <x-slot name="reportTable">
                        <x-system.tables.reports.leaves.leaves :leaves="$acceptedLeaves" id="acceptedLeaves">
                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <h4 class="text-center">Accepted Leaves Report</h4>
                                    <!-- display the time of printing the report -->
                                    <p class="text-muted text-end">Report generated on: {{ now()->format('Y-m-d H:i:s') }}
                                    </p>
                                    <p class="text-muted text-end">Total Accepted Leaves: {{ $acceptedLeaves->count() }}</p>
                                </div>
                            </div>
                        </x-system.tables.reports.leaves.leaves>
                    </x-slot>
                </x-system.reports.generic-report>
            </div>
        </div>
    @endsection
