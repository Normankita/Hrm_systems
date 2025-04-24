@extends('layouts.system')

@section('content')
<div class="container mt-4">
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-person-circle me-2"></i>
                Employee Profile - {{ $employee->first_name }} {{ $employee->last_name }}
            </h4>
        </div>
        <div class="card-body">
            <div class="row gy-3">
                <div class="col-md-6">
                    <strong>Full Name:</strong>
                    <div>{{ $employee->full_name}}</div>
                </div>

                <div class="col-md-6">
                    <strong>Gender:</strong>
                    <div>{{ ucfirst($employee->gender) }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Date of Birth:</strong>
                    <div>{{ \Carbon\Carbon::parse($employee->date_of_birth)->format('d M, Y') }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Email:</strong>
                    <div>{{ $employee->email }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Phone Number:</strong>
                    <div>{{ $employee->phone_number }}</div>
                </div>

                <div class="col-md-6">
                    <strong>National ID:</strong>
                    <div>{{ $employee->national_id }}</div>
                </div>

                <div class="col-md-6">
                    <strong>TIN Number:</strong>
                    <div>{{ $employee->tin_number ?? 'N/A' }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Marital Status:</strong>
                    <div>{{ $employee->marital_status ?? 'N/A' }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Residential Address:</strong>
                    <div>{{ $employee->residential_address }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Employee Type:</strong>
                    <div>{{ ucfirst($employee->employee_type) }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Date of Hire:</strong>
                    <div>{{ \Carbon\Carbon::parse($employee->date_of_hire)->format('d M, Y') }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Department:</strong>
                    <div>{{ $employee->department->name ?? 'N/A' }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Designation (Role):</strong>
                    <div>{{ $employee->role->name ?? 'N/A' }}</div>
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
                <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
