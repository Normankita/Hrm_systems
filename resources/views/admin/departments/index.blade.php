@extends('layouts.system')


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Departments</h4>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-nowrap">
                            <thead class="table-light text-lime">
                                <tr>
                                    <th colspan="10" class="text-center">Employee List</th>
                                </tr>
                                <tr>
                                    <th colspan="10" class="text-start">Total Employees: {{ $employees->count() }}</th>
                                </tr>
                                {{-- <tr>
                    <th colspan="10" class="text-center">Date: {{ \Carbon\Carbon::now()->format('d M Y') }}</th>
                </tr>
                <tr>
                    <th colspan="10" class="text-center">Time: {{ \Carbon\Carbon::now()->format('h:i A') }}</th>
                </tr> --}}
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departments as $department)

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection
