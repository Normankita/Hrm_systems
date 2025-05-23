@extends('layouts.system')

@section('content')
@can('view_leave')
    <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="container py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="{{ route('employees.leave.status') }}" class="btn btn-primary">Back to Leave Requests</a>
                    </div>
                    <h2 class="mb-4">Leave Request Details</h2>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong>Employee:</strong> {{ $leave->employee->full_name }} {{ $leave->employee->last_name }}
                    </div>

                    <div class="mb-3">
                        <strong>Leave Type:</strong> {{ $leave->leaveType->name }}
                    </div>

                    <div class="mb-3">
                        <strong>Start Date:</strong> {{ \Carbon\Carbon::parse($leave->start_date)->toFormattedDateString() }}
                    </div>

                    <div class="mb-3">
                        <strong>End Date:</strong> {{ \Carbon\Carbon::parse($leave->end_date)->toFormattedDateString() }}
                    </div>

                    <div class="mb-3">
                        <strong>Reason:</strong> {{ $leave->reason }}
                    </div>

                    <div class="mb-3">
                        <strong>Status:</strong> 
                        <span class="badge 
                            @if($leave->status == 'approved') badge-success 
                            @elseif($leave->status == 'pending') badge-warning 
                            @elseif($leave->status == 'rejected') badge-danger
                            @endif">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </div>

                    <!-- Edit and Delete Buttons -->
                    @if ($leave->status!=='approved' && $leave->status!=='rejected')
                    <div class="mb-3">
                       @can('edit_leave')
                            <a href="{{ route('employees.leave.edit', $leave->id) }}" class="mx-10 btn btn-lg btn-dark p-1 px-6">Edit</a>
                       @endcan

                        <!-- Delete Form -->
                        @can('delete_leave')
                            <form action="{{ route('employees.leave.destroy', $leave->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="mx-10 btn btn-lg btn-danger p-1 px-6" onclick="return confirm('Are you sure you want to delete this leave request?')">Delete </button>
                        </form>
                        @endcan
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection
