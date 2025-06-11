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
                             <div class="row">
                                 <div class="col-md-12">
                                     <p class="text-muted">
                                         Note: The table above shows all rejected leaves. You can filter the data by date, employee, or leave type using the search functionality.
                                     </p>
                                 </div>
                             </div>
                         </x-system.tables.reports.leaves.leaves>
                    </x-slot>
                </x-system.reports.generic-report>
            </div>
        </div>
    @endsection
