@props(['payrolls', 'title', 'backRoute' => null,
'viewRoute' => 'employee.manage.payrolls.show'])

@can('view_payment')
    <div class="row">
        <div v-if="!pageComplete" class="col-md-12">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="col-md-12 d-none" id="main">
            <div class="card">
                <div class="card-body ">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">{{ $title ?? '' }} Payroll List</h3>
                        @if(auth()->user()->roles()->has('ADMIN') ||
                                auth()->user()->hasPermissionTo('create_payment'))
                            @if ($title === 'Pending' || $title === 'All')
                                <button v-if="!formSubmmitted" type="button" v-on:click="approveSelected"
                                    class="btn btn-primary" @if ($payrolls->count() < 1) disabled @endif>
                                    <i class="mdi mdi-cash-multiple"></i> Approve Selected
                                </button>
                            @endif
                        @endif
                    </div>
                    <div class="table-responsive">
                        <span>Total Payrolls: {{ $payrolls->count() }}</span>
                        <table
                            class="table dtp-table table-bordered
                            table-hover align-middle text-nowrap">
                            <div>
                                <label for="" class="px-3">Select All</label>
                                <input type="checkbox" id="all-checker" name="all_checker">
                            </div>
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
                                        <td>{{ $key + 1 }}
                                            @if ($payroll->status === 'pending')
                                                <input type="checkbox" class="row-checker" value="{{ $payroll->id }}">
                                            @endif
                                        </td>
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
                                            <x-system.btn-view
                                                :route="route($viewRoute, $payroll).'?back='.$backRoute" text="View" />
                                            @if(auth()->user()->roles()->has('ADMIN') ||
                                                auth()->user()->hasPermissionTo('reject_payment'))
                                                @if ($payroll->status !== 'approved' && $payroll->status !== 'rejected')
                                                    <x-system.modal-button
                                                        class="btn btn-outline-danger p-1 btn-sm mdi mdi-close"
                                                        id="rejectPayroll{{ $payroll->id }}" text="Reject" textColor="" />
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No payrolls found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @foreach ($payrolls as $payroll)
                            @if ($payroll->status == 'pending')
                             @if(auth()->user()->roles()->has('ADMIN') ||
                                                auth()->user()->hasPermissionTo('reject_payment'))
                                    <x-system.modal id="rejectPayroll{{ $payroll->id }}" title="Reject Payroll">
                                        <div>
                                            <h3>Reject: <b>{{ $payroll->employee->full_name }}'s</b> Payroll</h3>
                                        </div>
                                        <form action="{{ route('employee.manage.payroll.employees.reject', $payroll) }}"
                                            method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="reason{{ $payroll->id }}" class="form-label">Reason for
                                                    Rejection</label>
                                                <textarea class="form-control" name="reason" id="reason-{{ $payroll->id }}" rows="4" required></textarea>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn-sm btn-danger">Submit
                                                    Rejection</button>
                                            </div>
                                        </form>
                                    </x-system.modal>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcan
