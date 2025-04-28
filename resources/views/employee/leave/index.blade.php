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
                    <table class="table table-bordered table-hover align-middle text-nowrap">
                        <thead class="table-light text-lime">

                              <tr>
                                <th></th>
                                <th>Leave Type</th>
                                <th>Start date</th>
                                <th>End date</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaves as $key => $leave)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $leave->leaveType->name?? 'n/A'}}</td>
                                    {{-- <td>{{ \Carbon\Carbon::parse($leave->date_of_birth)->format('d M Y') }}</td> --}}
                                    <td>{{ $leave->start_date }}</td>
                                    <td>{{ $leave->end_date }}</td>
                                    <td>{{ $leave->reason ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge">
                                            @if($leave->status == 'approved')
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
                                        <a
                                            href="{{ route('employees.leave.show', $leave->id) }}"
                                            class="btn btn-outline-dark p-1  btn-sm mdi
                                             mdi-eye-outline"> View &nbsp </a>
                                        <a
                                        href="{{ route('employees.leave.edit',
                                         $leave->id) }}"
                                          class="btn btn-outline-dark btn-sm p-1 mx-1 mdi mdi-pencil">&nbsp Edit &nbsp </a>
                                        <form action="{{ route('employees.leave.destroy', $leave) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm p-1
                                            mdi mdi-close">&nbsp Delete</button>
                                        </form>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">No leaves found.</td>
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
