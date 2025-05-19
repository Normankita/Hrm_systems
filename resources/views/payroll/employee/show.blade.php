@extends('layouts.system')

@section('content')
    <x-system.displays.employee-profile :employee="$employee" prefix="payroll.employees" :attachments="$attachments" :pay_grades="$pay_grades" />

    <div class="col-12 mt-5">
        <div class="card">
            <div class="card-body ">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0">Employee Payrolls</h3>
                </div>

                <div class="table-responsive">
                    <span>Total payrollss: {{ $payrolls->count() }}</span>
                    <table class="table table-bordered table-hover align-middle text-nowrap">
                        <thead class="table-light text-dark">

                            <tr>
                                <th></th>
                                <th>Date</th>
                                <th>Basic Salary</th>
                                <th>Gross Salary</th>
                                <th>Net Salary</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payrolls as $key => $payroll)
                                <tr class="text-dark">
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $payroll->payroll_date }}</td>
                                    {{-- <td>{{ \Carbon\Carbon::parse($payroll->date_of_birth)->format('d M Y') }}</td> --}}
                                    <td>{{ $payroll->basic_salary }}</td>
                                    <td>{{ $payroll->gross_salary }}</td>
                                    <td>{{ $payroll->net_salary }}</td>
                                    <td>
                                        <span
                                            class="badge 
        {{ $payroll->status === 'approved'
            ? 'bg-success'
            : ($payroll->status === 'rejected'
                ? 'bg-danger'
                : 'bg-warning text-dark') }}">
                                            {{ ucfirst($payroll->status) }}
                                        </span>
                                    </td>

                                    <td>
                                         <x-system.modal-button class="btn btn-outline-dark p-1  btn-sm mdi mdi-eye-outline"
                                    id="viewPayroll{{ $payroll->id }}" text="View" textColor="" />
                                    </td>

                                </tr>
                                <x-system.modal size="modal-lg" id="viewPayroll{{ $payroll->id }}" title="Payroll Details">
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
                                    <td colspan="10" class="text-center text-muted">No payrolls found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
