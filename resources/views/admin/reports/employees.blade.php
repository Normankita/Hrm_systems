@extends('layouts.system')

@section('content')
    <div class="card shadow rounded">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-white">Employees Report</h4>
            <div class="d-flex gap-2">
                <a class="btn btn-light btn-sm" href="{{ route('admin.reports.employees.export', request()->query() + ['format' => 'csv']) }}">
                    Export CSV
                </a>
                <a class="btn btn-light btn-sm" href="{{ route('admin.reports.employees.export', request()->query() + ['format' => 'pdf']) }}">
                    Export PDF
                </a>
            </div>
        </div>
        <div class="card-body">
            <form class="row g-2 mb-3" method="GET" action="{{ route('admin.reports.employees') }}">
                <div class="col-md-3">
                    <input class="form-control" type="number" name="department_id" value="{{ request('department_id') }}"
                        placeholder="Department ID">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="state" value="{{ request('state') }}"
                        placeholder="State (active/suspended/...)">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="employee_type" value="{{ request('employee_type') }}"
                        placeholder="Employee type">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.reports.employees') }}">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped w-100" id="employees-report-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Type</th>
                            <th>State</th>
                            <th>Department</th>
                            <th>Date of hire</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            $('#employees-report-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                lengthMenu: [25, 50, 100, 250, 500],
                order: [
                    [0, 'desc']
                ],
                ajax: {
                    url: "{{ route('admin.reports.employees.data') }}",
                    data: function(d) {
                        d.department_id = "{{ request('department_id') }}";
                        d.state = "{{ request('state') }}";
                        d.employee_type = "{{ request('employee_type') }}";
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'employee_type',
                        name: 'employee_type'
                    },
                    {
                        data: 'state',
                        name: 'state'
                    },
                    {
                        data: 'department',
                        name: 'department.name',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date_of_hire',
                        name: 'date_of_hire'
                    },
                ],
            });
        });
    </script>
@endsection

