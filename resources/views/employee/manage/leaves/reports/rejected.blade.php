@extends('layouts.system')

@section('content')
    <!-- a table to show rejected leaves -->
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-header bg-danger">
                <h4 class="mb-0 text-white">Rejected Leaves</h4>
            </div>
            <div class="card-body">
                <x-system.reports.generic-report id="rejectedLeaves">
                    <x-slot name="viewTable">
                        <x-system.tables.reports.leaves.leaves
                             :leaves="$rejectedLeaves" class="dt-table" />
                    </x-slot>
                    <x-slot name="reportTable">
                         <x-system.tables.reports.leaves.leaves
                             :leaves="$rejectedLeaves" id="rejectedLeaves">
                             <div class="row justify-content-center">
                                 <div class="col-md-10">
                                    <h4 class="text-center">Rejected Leaves Report</h4>
                                    <!-- display the time of printing the report -->
                                    <p class="text-muted text-end">Report generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
                                    <p class="text-muted text-end">Total Rejected Leaves: {{ $rejectedLeaves->count() }}</p>
                                 </div>
                             </div>
                         </x-system.tables.reports.leaves.leaves>
                    </x-slot>
                </x-system.reports.generic-report>
            </div>
        </div>
    @endsection
