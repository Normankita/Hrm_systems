@extends('layouts.system')

@section('content')
<div class="container mt-4">
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Employee Profile - {{ $employee->first_name }} {{ $employee->last_name }}</h4>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-6 mb-3">
                    <strong>Full Name:</strong>
                    <p>{{ $employee->first_name }} {{ $employee->last_name }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Gender:</strong>
                    <p>{{ $employee->gender }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Date of Birth:</strong>
                    <p>{{ \Carbon\Carbon::parse($employee->date_of_birth)->format('d M, Y') }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Email:</strong>
                    <p>{{ $employee->email }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Phone Number:</strong>
                    <p>{{ $employee->phone_number }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>National ID:</strong>
                    <p>{{ $employee->national_id }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>TIN Number:</strong>
                    <p>{{ $employee->tin_number ?? 'N/A' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Marital Status:</strong>
                    <p>{{ $employee->marital_status ?? 'N/A' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Residential Address:</strong>
                    <p>{{ $employee->residential_address }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Employee Type:</strong>
                    <p>{{ $employee->employee_type }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Date of Hire:</strong>
                    <p>{{ \Carbon\Carbon::parse($employee->date_of_hire)->format('d M, Y') }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Department:</strong>
                    <p>{{ $employee->department->name }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Designation (Role):</strong>
                    <p>{{ $employee->role->name }}</p>
                </div>
            </div>

            <div class="text-end">
                <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Back to List</a>
                <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-warning">Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection
