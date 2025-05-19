@props(['employee'])
<div class="card">
    <div class="card-body ">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Employee Payrolls</h3>
        </div>

        <div class="table-responsive">
            <span>Total payrollss: {{ $employee->payrolls->count() }}</span>
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
                <tbody>
                    @forelse($employee->payrolls as $key => $payroll)
                        <tr class="text-dark">
                            <td>{{ ++$key }}</td>
                            <td>{{ $payroll->payroll_date }}</td>
                            {{-- <td>{{ \Carbon\Carbon::parse($payroll->date_of_birth)->format('d M Y') }}</td> --}}
                            <td>{{ $payroll->basic_salary }}</td>
                            <td>{{ $payroll->gross_salary }}</td>
                            <td>{{ $payroll->net_salary }}</td>
                            <td>
                                <span
                                    class="badge 
        {{ $payroll->status === 'approved'
            ? 'bg-success'
            : ($payroll->status === 'rejected'
                ? 'bg-danger'
                : 'bg-warning text-dark') }}">
                                    {{ ucfirst($payroll->status) }}
                                </span>
                            </td>

                            <td>
                                <form
                                    action="{{ route('payroll.show', ['employee' => $employee->id, 'payroll' => $payroll->id]) }}"
                                    method="GET">
                                    <button type="submit" class="btn btn-outline-dark p-1  btn-sm mdi mdi-eye-outline">
                                        &nbsp View &nbsp</button>
                                </form>

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
