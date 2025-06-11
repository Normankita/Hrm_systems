@extends('layouts.system')
@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-header bg-primary">
                <h4 class="mb-0 text-white">Leave Reports</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <a href="{{ route('employee.manage.leave.reports.rejected') }}">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h5 class="card-title font-weight-bold">
                                        Rejected Leaves
                                    </h5>
                                    <p class="card-text">
                                        Total:
                                    </p>
                                    <h2 class="text-primary">980000</h2>
                                </div>
                            </div>
                        </a>
                    </div>
            </div>
        </div>
    </div>
@endsection
