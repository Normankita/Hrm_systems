@extends('layouts.system')

@section('content')
    <div class="card shadow rounded">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-white">Attendance Report</h4>
            <div class="d-flex gap-2">
                <a class="btn btn-light btn-sm"
                    href="{{ route('employee.manage.reports.attendance.export', request()->query() + ['format' => 'csv']) }}">
                    Export CSV
                </a>
                <a class="btn btn-light btn-sm"
                    href="{{ route('employee.manage.reports.attendance.export', request()->query() + ['format' => 'pdf']) }}">
                    Export PDF
                </a>
            </div>
        </div>
        <div class="card-body">
            <form class="row g-2 mb-3" method="GET" action="{{ route('employee.manage.reports.attendance') }}">
                <div class="col-md-3">
                    <input class="form-control" type="date" name="from" value="{{ request('from') }}">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="date" name="to" value="{{ request('to') }}">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="status" value="{{ request('status') }}"
                        placeholder="Status">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="number" name="employee_id" value="{{ request('employee_id') }}"
                        placeholder="Employee ID">
                </div>
                <div class="col-md-12 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <a class="btn btn-outline-secondary" href="{{ route('employee.manage.reports.attendance') }}">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped w-100" id="attendance-report-table-employee">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Date</th>
                            <th>Check in</th>
                            <th>Check out</th>
                            <th>Status</th>
                            <th>Remarks</th>
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
            $('#attendance-report-table-employee').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                lengthMenu: [25, 50, 100, 250, 500],
                order: [
                    [3, 'desc']
                ],
                ajax: {
                    url: "{{ route('employee.manage.reports.attendance.data') }}",
                    data: function(d) {
                        d.from = "{{ request('from') }}";
                        d.to = "{{ request('to') }}";
                        d.status = "{{ request('status') }}";
                        d.employee_id = "{{ request('employee_id') }}";
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'employee',
                        name: 'employee.full_name',
                        orderable: false
                    },
                    {
                        data: 'department',
                        name: 'employee.department.name',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'attendance_date',
                        name: 'attendance_date'
                    },
                    {
                        data: 'check_in_time',
                        name: 'check_in_time'
                    },
                    {
                        data: 'check_out_time',
                        name: 'check_out_time'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks',
                        orderable: false
                    },
                ],
            });
        });
    </script>
@endsection

