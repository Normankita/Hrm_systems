@extends('layouts.system')

@section('content')
    @can('view_employees')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body ">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="mb-0">Employee Directory</h3>
                            @can('create_employees')
                                <a href="{{ route('employee.manage.employees.create') }}" class="btn btn-primary">Add Employee</a>
                            @endcan
                        </div>

                        <div class="table-responsive">
                            <span>Total Employees: {{ $employees->count() }}</span>
                            <table class="table table-bordered table-hover align-middle text-nowrap">
                                <thead class="table-light text-dark">

                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Department</th>
                                        <th>Employee Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employees as $key => $employee)
                                        <tr class="text-dark">
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $employee->full_name }}</td>
                                            {{-- <td>{{ \Carbon\Carbon::parse($employee->date_of_birth)->format('d M Y') }}</td> --}}
                                            <td>{{ $employee->phone_number }}</td>
                                            <td>{{ $employee->email }}</td>
                                            <td>{{ $employee->department->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge text-dark">
                                                    {{ $employee->employee_type }}
                                                </span>
                                            </td>
                                            <td>
                                                <x-system.btn-view :key="$key" :route="route('employee.manage.employees.show', $employee->id)" />
                                                @can('edit_employees')
                                                    <x-system.btn-edit :key="$key" :route="route('employee.manage.employees.edit', $employee->id)" />
                                                @endcan
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">No employees found.</td>
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
