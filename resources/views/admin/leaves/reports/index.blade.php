@extends('layouts.system')
@section('content')
        <div class="card shadow rounded">
            <div class="card-header bg-primary">
                <h4 class="mb-0 text-white">Leave Reports</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <x-system.reports.report-card-anchor title="Rejected Leaves" :route="route('admin.leave.reports.rejected')">
                        </x-system.reports.report-card-anchor>
                    </div>

                    <div class="col-md-4">
                        <x-system.reports.report-card-anchor title="Accepted Leaves" :route="route('admin.leave.reports.accepted')">
                        </x-system.reports.report-card-anchor>
                    </div>

                         <div class="col-md-4">
                        <x-system.reports.report-card-anchor title="Pending Leaves" :route="route('admin.leave.reports.pending')">
                        </x-system.reports.report-card-anchor>
                    </div>
                </div>
            </div>
        </div>
    @endsection
