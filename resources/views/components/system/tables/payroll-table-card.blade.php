@props(['payrolls', 'title'])
@can('view_payment')
    <div class="card">
        <div class="card-body ">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">{{ $title ?? '' }} Payroll List</h3>
                @can('create_payment')
                    @if ($title === 'Pending' || $title === 'All')
                        <form action="{{ route('employee.manage.payroll.employees.approveAll') }}" method="POST" class="mb-0">
                            @csrf
                            <button type="submit" class="btn btn-primary" @if ($payrolls->count() < 1) disabled @endif>
                                <i class="mdi mdi-cash-multiple"></i> Approve all Pending Payrolls
                            </button>
                        </form>
                    @endif
                @endcan
            </div>
            <div class="table-responsive">
                <span>Total Payrolls: {{ $payrolls->count() }}</span>
                <table class="table table-bordered table-hover align-middle text-nowrap">
                    <thead class="table-light text-dark">
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Pay Grade</th>
                            <th>Base Salary</th>
                            <th>Period</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $key => $payroll)
                            <tr class="text-dark">
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $payroll->employee->full_name ?? 'N/A' }}</td>
                                <td>{{ $payroll->pay_grade->name ?? 'N/A' }}</td>
                                <td>{{ number_format($payroll->basic_salary, 2) }}</td>
                                <td>{{ $payroll->period ?? 'N/A' }}</td>
                                <td>
                                    <span
                                        class="badge {{ $payroll->status === 'approved'
                                            ? 'bg-success'
                                            : ($payroll->status === 'rejected'
                                                ? 'bg-danger'
                                                : 'bg-warning text-dark') }}">
                                        {{ ucfirst($payroll->status) }}
                                    </span>
                                </td>

                                <td>{{ $payroll->created_at->format('d M Y') }}</td>
                                <td>
                                    <x-system.btn-view :route="route('employee.manage.payrolls.show', $payroll)" text="View" />
                                    @can('reject_payment')
                                        @if ($payroll->status !== 'approved' && $payroll->status !== 'rejected')
                                            <x-system.modal-button class="btn btn-outline-danger p-1 btn-sm mdi mdi-close"
                                                id="rejectPayroll{{ $payroll->id }}" text="Reject" textColor="" />
                                        @endif
                                    @endcan

                                </td>
                            </tr>
                            @if ($payroll->status == 'pending')
                                @can('reject_payment')
                                    <x-system.modal id="rejectPayroll{{ $payroll->id }}" title="Reject Payroll">

                                        <form action="{{ route('employee.manage.payroll.employees.reject', $payroll) }}"
                                            method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="reason{{ $payroll->id }}" class="form-label">Reason for
                                                    Rejection</label>
                                                <textarea class="form-control" name="reason" id="reason{{ $payroll->id }}" rows="4" required></textarea>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="button" class="btn btn-secondary me-2"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Submit Rejection</button>
                                            </div>
                                        </form>
                                    </x-system.modal>
                                @endcan
                            @endif
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No payrolls found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endcan
