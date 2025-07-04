@extends('layouts.system')


@section('content')
    @can('view_loans')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body ">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="mb-0">Loans</h3>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-nowrap">
                                <thead class="table-light text-lime">
                                    <tr>
                                        <th></th>
                                        <th>Employee</th>
                                        <th>Loan Type</th>
                                        <th>Issued on</th>
                                        <th>Months</th>
                                        <th>Amount Loaned</th>
                                        <th>Amount to return</th>
                                        <th>status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($loans as $key => $loan)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $loan->employee->full_name }}</td>
                                            <td>{{ $loan->loan_type }}</td>
                                            <td>{{ $loan->issued_date }}</td>
                                            <td>{{ $loan->months_to_pay }}</td>
                                            <td>{{ number_format($loan->amount) }}</td>
                                            <td>{{ number_format($loan->total_payable) }}</td>
                                            <td>
                                                <span class="badge text-dark">
                                                    {{ $loan->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <x-system.btn-view :route="route('employee.manage.loans.show', $loan)" :key="$key . '_' . $index" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection
