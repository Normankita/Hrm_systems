@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body ">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">leave Directory</h3>
                        <a href="{{ route('employees.leave.request') }}" class="btn btn-primary">Request leave</a>
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
                                               <x-system.btn-edit :key="$key" :route="route('employees.leave.edit', $leave->id)" />
                                                <x-system.btn-delete :key="$key" :route="route('employees.leave.destroy', $leave)" />                                                   
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
@endsection
