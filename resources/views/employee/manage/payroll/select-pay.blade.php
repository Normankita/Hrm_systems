@extends('layouts.system')

@section('content')
@can('create_payroll')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <form method="POST" action="{{ route('employee.manage.payrolls.generateSelected') }}">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="mb-0">Select for Payment</h3>
                            <button type="submit" class="btn btn-primary">Generate for Selected Employees</button>
                        </div>

                        <div class="table-responsive">
                            <span>Total Employees: {{ $employees->count() }}</span>
                            <table class="table table-bordered table-hover align-middle text-nowrap">
                                <thead class="table-light text-dark">
                                    <tr>
                                        <th>Select</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Department</th>
                                        <th>Employee Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employees as $key => $employee)
                                        <tr class="text-dark">
                                            <td>
                                                <input type="checkbox" name="selected_employees[]" value="{{ $employee->id }}">
                                            </td>
                                            <td>{{ $employee->full_name }}</td>
                                            <td>{{ $employee->phone_number }}</td>
                                            <td>{{ $employee->email }}</td>
                                            <td>{{ $employee->department->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge text-dark">
                                                    {{ $employee->employee_type }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No employees found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endcan
@endsection
