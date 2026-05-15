@extends('layouts.system')

@section('content')
    <div class="card shadow rounded">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-white">Payroll Report</h4>
            <div class="d-flex gap-2">
                <a class="btn btn-light btn-sm" href="{{ route('admin.reports.payroll.export', request()->query() + ['format' => 'csv']) }}">
                    Export CSV
                </a>
                <a class="btn btn-light btn-sm" href="{{ route('admin.reports.payroll.export', request()->query() + ['format' => 'pdf']) }}">
                    Export PDF
                </a>
            </div>
        </div>
        <div class="card-body">
            <form class="row g-2 mb-3" method="GET" action="{{ route('admin.reports.payroll') }}">
                <div class="col-md-3">
                    <input class="form-control" type="text" name="period" value="{{ request('period') }}"
                        placeholder="Period (YYYY-MM)">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="status" value="{{ request('status') }}"
                        placeholder="Status">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="number" name="employee_id" value="{{ request('employee_id') }}"
                        placeholder="Employee ID">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.reports.payroll') }}">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped w-100" id="payroll-report-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Period</th>
                            <th>Payroll date</th>
                            <th>Basic</th>
                            <th>Gross</th>
                            <th>Net</th>
                            <th>Status</th>
                            <th>Approved at</th>
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
            $('#payroll-report-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                lengthMenu: [25, 50, 100, 250, 500],
                order: [
                    [0, 'desc']
                ],
                ajax: {
                    url: "{{ route('admin.reports.payroll.data') }}",
                    data: function(d) {
                        d.period = "{{ request('period') }}";
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
                        data: 'period',
                        name: 'period'
                    },
                    {
                        data: 'payroll_date',
                        name: 'payroll_date'
                    },
                    {
                        data: 'basic_salary',
                        name: 'basic_salary'
                    },
                    {
                        data: 'gross_salary',
                        name: 'gross_salary'
                    },
                    {
                        data: 'net_salary',
                        name: 'net_salary'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'approved_at',
                        name: 'approved_at'
                    },
                ],
            });
        });
    </script>
@endsection

