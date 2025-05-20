@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Pay Grade Details</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Name:</div>
                <div class="col-md-8">{{ $payGrade->name }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Base Salary:</div>
                <div class="col-md-8">{{ number_format($payGrade->base_salary, 2) }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Maximum Salary:</div>
                <div class="col-md-8">{{ number_format($payGrade->max_salary, 2) }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Base Month Count:</div>
                <div class="col-md-8">{{ $payGrade->base_month_count }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Description:</div>
                <div class="col-md-8">{{ $payGrade->description ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('employee.manage.paygrades.edit', $payGrade->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('employee.manage.paygrades.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection
