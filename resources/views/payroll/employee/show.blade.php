@extends('layouts.system')

@section('content')

<x-system.displays.employee-profile
:employee="$employee"
prefix="payroll.employees"
:attachments="$attachments"
:pay_grades="$pay_grades"

/>

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
                        <tbody >
                            @forelse($payrolls as $key => $payroll)
                                <tr class="text-dark">
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $payroll->payroll_date }}</td>
                                    {{-- <td>{{ \Carbon\Carbon::parse($payroll->date_of_birth)->format('d M Y') }}</td> --}}
                                    <td>{{ $payroll->basic_salary }}</td>
                                    <td>{{ $payroll->gross_salary }}</td>
                                    <td>{{ $payroll->net_salary }}</td>
                                    <td>
                                        <span class="badge">
                                            {{ $payroll->status }}
                                        </span>
                                    </td>
                                  
                                    <td>
                                      <x-system.btn-view :key="$key" :route="route('hr.employees.show', $payroll->id)" />
                                    </td>
                                       
                                </tr>
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