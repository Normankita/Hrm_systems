@extends('layouts.system')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Leave Details Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-1">Leave Request Details</h4>
                            <p class="text-muted">Review and manage leave request</p>
                        </div>
                        <div>
                            <a href="{{ route('hr.leave.index') }}" class="btn btn-light">
                                <i class="mdi mdi-arrow-left me-1"></i> Back to List
                            </a>
                        </div>
                    </div>

                    <!-- Leave Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Employee Information</h5>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <p class="text-muted mb-0">Employee Name</p>
                                        </div>
                                        <div class="col-sm-8">
                                            <p class="mb-0">{{ $leave->employee->full_name }}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <p class="text-muted mb-0">Department</p>
                                        </div>
                                        <div class="col-sm-8">
                                            <p class="mb-0">{{ $leave->employee->department->name }}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <p class="text-muted mb-0">Employee ID</p>
                                        </div>
                                        <div class="col-sm-8">
                                            <p class="mb-0">{{ $leave->employee->id }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Leave Details</h5>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <p class="text-muted mb-0">Leave Type</p>
                                        </div>
                                        <div class="col-sm-8">
                                            <p class="mb-0">{{ $leave->leaveType->name }}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <p class="text-muted mb-0">Start Date</p>
                                        </div>
                                        <div class="col-sm-8">
                                            <p class="mb-0">{{ $leave->start_date }}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <p class="text-muted mb-0">End Date</p>
                                        </div>
                                        <div class="col-sm-8">
                                            <p class="mb-0">{{ $leave->end_date }}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <p class="text-muted mb-0">Status</p>
                                        </div>
                                        <div class="col-sm-8">
                                            <span class="badge bg-{{ $leave->status === 'approved' ? 'success' : ($leave->status === 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($leave->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reason and Action -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Leave Reason</h5>
                                    <p class="mb-4">{{ $leave->reason }}</p>

                                    @if($leave->status === 'pending')
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                            <i class="mdi mdi-close-circle me-1"></i> Reject
                                        </button>
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                                            <i class="mdi mdi-check-circle me-1"></i> Approve
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('hr.leave.approve', $leave->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Approve Leave Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="approval_comment" class="form-label">Comment (Optional)</label>
                        <textarea class="form-control" id="approval_comment" name="comment" rows="3" placeholder="Add a comment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Leave</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('hr.leave.reject', $leave->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Leave Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_comment" class="form-label">Reason for Rejection</label>
                        <textarea class="form-control" id="rejection_comment" name="comment" rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Leave</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
