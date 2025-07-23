@extends('layouts.system')

@section('content')
    @can('view_leave')
        <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body ">
                    <div>
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <div class="card border-primary">
                                        <div class="card-body text-center">
                                            <h6 class="text-primary mb-1">Leave Balance</h6>
                                            <h4 class="mb-0">{{ $leaveBalance ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="card border-info">
                                        <div class="card-body text-center">
                                            <h6 class="text-info mb-1">Total Leave Days</h6>
                                            <h4 class="mb-0">{{ $totalBalance ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="card border-warning">
                                        <div class="card-body text-center">
                                            <h6 class="text-warning mb-1">Spent Leave Days</h6>
                                            <h4 class="mb-0">{{ $leaveDaysUsed ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <h6 class="text-success mb-1">UnCompensated Days</h6>
                                            <h4 class="mb-0">{{ $uncompensatedLeaves ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="card border-danger">
                                        <div class="card-body text-center">
                                            <h6 class="text-danger mb-1">Compensated Days</h6>
                                            <h4 class="mb-0">{{ $compensatedLeaves ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">leave Directory</h3>
                        @can('request_leave')
                            <a href="{{ route('employees.leave.request') }}" class="btn btn-primary">Request leave</a>
                        @endcan
                    </div>

                    <div class="table-responsive">
                        <span>Total Leave Requests: {{ $leaves->count() }}</span>
                        <x-system.table>
                            <x-slot:head>
                                <x-system.table-head>
                                    <tr>
                                        <th></th>
                                        <th>Leave Type</th>
                                        <th>Start date</th>
                                        <th>End date</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </x-system.table-head>
                            </x-slot:head>
                            <x-slot:body>
                                <x-system.table-body>
                                    @forelse($leaves as $key => $leave)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $leave->leaveType->name ?? 'n/A' }}</td>
                                            {{-- <td>{{ \Carbon\Carbon::parse($leave->date_of_birth)->format('d M Y') }}</td> --}}
                                            <td>{{ $leave->start_date }}</td>
                                            <td>{{ $leave->end_date }}</td>
                                            <td class="text-truncate" style="max-width: 500px;">{{ $leave->reason ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge">
                                                    @if ($leave->status == 'approved')
                                                        <span class="badge bg-success text-dark">Approved</span>
                                                    @elseif($leave->status == 'pending')
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @elseif($leave->status == 'rejected')
                                                        <span class="badge bg-danger text-dark">Rejected</span>
                                                    @else
                                                        <span class="badge bg-secondary text-dark">Unknown</span>
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                <x-system.btn-view :key="$key" :route="route('employees.leave.show', $leave->id)" />
                                               @if ($leave->status!=='approved' && $leave->status!=='rejected')
                                               @can('edit_leave')
                                                   <x-system.btn-edit :key="$key" :route="route('employees.leave.edit', $leave->id)" />
                                               @endcan
                                               @can('delete_leave')
                                                    <x-system.btn-delete :key="$key" :route="route('employees.leave.destroy', $leave->id)" />
                                               @endcan
                                               @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">No leaves found.</td>
                                        </tr>
                                    @endforelse
                                </x-system.table-body>
                            </x-slot:body>
                        </x-system.table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
@endsection
