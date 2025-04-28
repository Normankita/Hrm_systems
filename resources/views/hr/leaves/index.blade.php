@extends('layouts.system')


@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body ">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0">Leave Information</h3>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-nowrap">
                        <thead class="table-light text-lime">

                              <tr>
                                <th></th>
                                <th>Employee</th>
                                <th>Leave Type</th>
                                <th>Starting In</th>
                                <th>End In</th>
                                <th>status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaves as $key => $leave)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $leave->employee->full_name }}</td>
                                    <td>{{ $leave->leaveType->name }}</td>
                                    <td>{{ ($leave->start_date) }}</td>
                                    <td>{{ $leave->end_date }}</td>
                                    <td>
                                        <span class="badge">
                                            {{ $leave->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('hr.leave.show', $leave) }}">
                                            <button class="btn btn-primary btn-sm">
                                                <i class="mdi mdi-eye"></i>
                                            </button>
                                        </a>
                                    </td>
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
