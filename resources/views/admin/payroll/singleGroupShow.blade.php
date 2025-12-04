@extends('layouts.system')

@section('content')
    @can('view_payroll')
        <div class="row">
            <div class="col-12">
                <x-back-button :route="route('admin.payrolls.index')" />
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body ">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="mb-0">Payroll: {{ $entrence_reference }}</h3>
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
                                                <x-system.btn-view
                                                    :route="route('admin.payrolls.show', $payroll)"
                                                    text="View" />
                                            </td>
                                        </tr>


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
    @endcan
@endsection
