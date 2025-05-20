@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body ">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Payroll List</h3>
                        <form action="{{ route('employees.generateAll') }}" method="POST" class="mb-0">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-cash-multiple"></i> Generate Payroll
                            </button>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <span>Total Payrolls: {{ $payrolls->count() }}</span>
                        <table class="table table-bordered table-hover align-middle text-nowrap">
                            <thead class="table-light text-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Pay Grade</th>
                                    <th>Base Salary</th>
                                    <th>Period</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payrolls as $key => $payroll)
                                    <tr class="text-dark">
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $payroll->employee->full_name ?? 'N/A' }}</td>
                                        <td>{{ $payroll->pay_grade->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($payroll->basic_salary, 2) }}</td>
                                        <td>{{ $payroll->period ?? 'N/A' }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $payroll->status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ ucfirst($payroll->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $payroll->created_at->format('d M Y') }}</td>
                                        <td>
                                            <x-system.modal-button
                                                class="btn btn-outline-dark p-1  btn-sm mdi mdi-eye-outline"
                                                id="viewPayroll{{ $payroll->id }}" text="View" textColor="" />



                                        </td>
                                    </tr>
                                    <x-system.modal size="modal-lg" id="viewPayroll{{ $payroll->id }}"
                                        title="Payroll Details">
                                        <div class="card shadow-sm border-0">
                                            <div class="card-body">
                                                {{-- Employee Info --}}
                                                <div class="row mb-3">
                                                    <div class="col-md-4 font-weight-bold">Employee Name:</div>
                                                    <div class="col-md-8">{{ $payroll->employee->full_name }}</div>
                                                </div>

                                                {{-- Payroll Period --}}
                                                <div class="row mb-3">
                                                    <div class="col-md-4 font-weight-bold">Payroll Period:</div>
                                                    <div class="col-md-8">{{ $payroll->period ?? 'N/A' }}</div>
                                                </div>

                                                {{-- Basic Salary --}}
                                                <div class="row mb-3">
                                                    <div class="col-md-4 font-weight-bold">Basic Salary:</div>
                                                    <div class="col-md-8">{{ number_format($payroll->basic_salary, 2) }}
                                                    </div>
                                                </div>

                                                {{-- Allowances --}}
                                                <div class="row mb-3">
                                                    <div class="col-md-4 font-weight-bold">Allowances:</div>
                                                    <div class="col-md-8">{{ number_format($payroll->allowances, 2) }}
                                                    </div>
                                                </div>

                                                {{-- Deductions --}}
                                                <div class="row mb-3">
                                                    <div class="col-md-4 font-weight-bold">Custom Deductions:</div>
                                                    <div class="col-md-8">{{ number_format($payroll->deductions, 2) }}
                                                    </div>
                                                </div>

                                                {{-- Statutory Contributions --}}
                                                <div class="row mb-3">
                                                    <div class="col-md-4 font-weight-bold">PAYE:</div>
                                                    <div class="col-md-8">{{ number_format($payroll->paye, 2) }}</div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 font-weight-bold">NSSF:</div>
                                                    <div class="col-md-8">{{ number_format($payroll->nssf, 2) }}</div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 font-weight-bold">PSSSF:</div>
                                                    <div class="col-md-8">{{ number_format($payroll->psssf, 2) }}</div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 font-weight-bold">SDL:</div>
                                                    <div class="col-md-8">{{ number_format($payroll->sdl, 2) }}</div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 font-weight-bold">WCF:</div>
                                                    <div class="col-md-8">{{ number_format($payroll->wcf, 2) }}</div>
                                                </div>

                                                {{-- Summary --}}
                                                <div class="row mt-4 mb-3">
                                                    <div class="col-md-4 font-weight-bold">Gross Salary:</div>
                                                    <div class="col-md-8">{{ number_format($payroll->gross_salary, 2) }}
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 font-weight-bold text-success">Net Salary (Take
                                                        Home):</div>
                                                    <div class="col-md-8 text-success font-weight-bold">
                                                        {{ number_format($payroll->net_salary, 2) }}</div>
                                                </div>

                                                {{-- Pay Grade --}}
                                                <div class="row mb-3">
                                                    <div class="col-md-4 font-weight-bold">Pay Grade:</div>
                                                    <div class="col-md-8">{{ $payroll->pay_grade->name ?? 'N/A' }}</div>
                                                </div>

                                                {{-- Approval Status --}}
                                                <div class="row mb-3">
                                                    <div class="col-md-4 font-weight-bold">Status:</div>
                                                    <div class="col-md-8 text-capitalize">
                                                        {{ $payroll->status ?? 'pending' }}</div>
                                                </div>

                                                @if ($payroll->status === 'rejected')
                                                    <div class="row mb-3">
                                                        <div class="col-md-4 font-weight-bold text-danger">Rejection Reason:
                                                        </div>
                                                        <div class="col-md-8 text-danger">{{ $payroll->rejection_reason }}
                                                        </div>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    </x-system.modal>


                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No payrolls found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
