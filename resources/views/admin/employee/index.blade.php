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
                            {{-- <tr>
                                <th colspan="10" class="text-center">Date: {{ \Carbon\Carbon::now()->format('d M Y') }}</th>
                            </tr>
                            <tr>
                                <th colspan="10" class="text-center">Time: {{ \Carbon\Carbon::now()->format('h:i A') }}</th>
                            </tr> --}}
                            <tr>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>DOB</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Type</th>
                                <th>Hire Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                <tr>
                                    <td>{{ $employee->full_name }}</td>
                                    <td>{{ $employee->gender }}</td>
                                    <td>{{ \Carbon\Carbon::parse($employee->date_of_birth)->format('d M Y') }}</td>
                                    <td>{{ $employee->phone_number }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>{{ $employee->company->name ?? 'N/A' }}</td>
                                    <td>{{ $employee->department->name ?? 'N/A' }}</td>
                                    <td>{{ $employee->designation->designation->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($employee->employee_type === 'Permanent') bg-success
                                            @elseif($employee->employee_type === 'Contract') bg-warning text-dark
                                            @else bg-secondary
                                            @endif">
                                            {{ $employee->employee_type }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($employee->date_of_hire)->format('d M Y') }}</td>
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
