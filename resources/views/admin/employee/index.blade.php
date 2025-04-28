@extends("layouts.system")

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body ">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0">Employee Directory</h3>
                    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">Add Employee</a>
                </div>

                <div class="table-responsive">
                    <span>Total Employees: {{ $employees->count() }}</span>
                    <table class="table table-bordered table-hover align-middle text-nowrap">
                        <thead class="table-light text-lime">

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
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $employee->full_name }}</td>
                                    {{-- <td>{{ \Carbon\Carbon::parse($employee->date_of_birth)->format('d M Y') }}</td> --}}
                                    <td>{{ $employee->phone_number }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>{{ $employee->department->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge">
                                            {{ $employee->employee_type }}
                                        </span>
                                    </td>
                                    <td>
                                      <x-system.btn-view :key="$key" :route="route('admin.employees.show', $employee->id)" />
                                      <x-system.btn-edit :key="$key" :route="route('admin.employees.edit', $employee->id)" />
                                       
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


@endsection
