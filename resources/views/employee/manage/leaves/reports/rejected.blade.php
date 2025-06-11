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
                        <x-system.tables.reports.leaves.rejected-leaves
                             :leaves="$rejectedLeaves" class="dt-table" />
                    </x-slot>
                    <x-slot name="reportTable">
                         <x-system.tables.reports.leaves.rejected-leaves
                             :leaves="$rejectedLeaves" id="rejectedLeaves"/>
                    </x-slot>
                </x-system.reports.generic-report>
            </div>
        </div>
    @endsection
