@extends('layouts.system') 

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Payroll Details - {{ $employee->full_name }}</h4>
        </div>
        <div class="card-body">
            {{-- SECTION: Basic Info --}}
            <h5 class="text-muted mb-3">Basic Info</h5>
            <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Payroll Date:</div>
                <div class="col-md-8">{{ $payroll->payroll_date }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Payroll Period:</div>
                <div class="col-md-8">{{ $payroll->period ?? 'N/A' }}</div>
            </div>

            {{-- SECTION: Salary --}}
            <h5 class="text-muted mt-4 mb-3">Salary Breakdown</h5>
            <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Basic Salary:</div>
                <div class="col-md-8">{{ number_format($payroll->basic_salary, 2) }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Allowances:</div>
                <div class="col-md-8">{{ number_format($payroll->allowances, 2) }}</div>
            </div>

            {{-- SECTION: Individual Deductions --}}
            <h5 class="text-muted mt-4 mb-3">Deductions</h5>
            @forelse ($deductions as $deduction)
                <div class="row mb-2">
                    <div class="col-md-4">{{ $deduction->name ?? 'Custom Deduction' }}</div>
                    <div class="col-md-8">{{ number_format($deduction->total_amount, 2) }}</div>
                </div>
            @empty
                <div class="text-muted">No deductions recorded.</div>
            @endforelse

            {{-- SECTION: Statutory Contributions --}}
            <h5 class="text-muted mt-4 mb-3">Statutory Contributions</h5>
            <div class="row mb-2"><div class="col-md-4">PAYE</div><div class="col-md-8">{{ number_format($payroll->paye, 2) }}</div></div>
            <div class="row mb-2"><div class="col-md-4">NSSF</div><div class="col-md-8">{{ number_format($payroll->nssf, 2) }}</div></div>
            <div class="row mb-2"><div class="col-md-4">PSSSF</div><div class="col-md-8">{{ number_format($payroll->psssf, 2) }}</div></div>
            <div class="row mb-2"><div class="col-md-4">SDL</div><div class="col-md-8">{{ number_format($payroll->sdl, 2) }}</div></div>
            <div class="row mb-2"><div class="col-md-4">WCF</div><div class="col-md-8">{{ number_format($payroll->wcf, 2) }}</div></div>

            {{-- SECTION: Summary --}}
            <h5 class="text-muted mt-4 mb-3">Summary</h5>
            <div class="row mb-2"><div class="col-md-4">Gross Salary</div><div class="col-md-8">{{ number_format($payroll->gross_salary, 2) }}</div></div>
            <div class="row mb-2 text-success font-weight-bold">
                <div class="col-md-4">Net Salary (Take Home)</div>
                <div class="col-md-8">{{ number_format($payroll->net_salary, 2) }}</div>
            </div>

            {{-- SECTION: Misc --}}
            <h5 class="text-muted mt-4 mb-3">Other Details</h5>
            <div class="row mb-2"><div class="col-md-4">Pay Grade</div><div class="col-md-8">{{ $payroll->pay_grade->name ?? 'N/A' }}</div></div>
            <div class="row mb-2">
                <div class="col-md-4">Status</div>
                <div class="col-md-8">
                    <span class="badge 
                        {{ $payroll->status === 'approved' ? 'bg-success' : 
                           ($payroll->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                        {{ ucfirst($payroll->status) }}
                    </span>
                </div>
            </div>

            @if ($payroll->status === 'rejected')
            <div class="row mb-2 text-danger">
                <div class="col-md-4">Rejection Reason</div>
                <div class="col-md-8">{{ $payroll->rejection_reason }}</div>
            </div>
            @endif

            {{-- Back Button --}}
            <div class="mt-4">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary " style="text-decoration: none; border: 1px dashed">← Back </a>

            </div>
        </div>
    </div>
</div>
@endsection
