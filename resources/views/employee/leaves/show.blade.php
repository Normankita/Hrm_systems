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
                                <a href="{{ route('employee.leave.index') }}" class="btn btn-light">
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
                                                <span
                                                    class="badge bg-{{ $leave->status === 'approved' ? 'success' : ($leave->status === 'rejected' ? 'danger' : 'warning') }}">
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
                                        {{-- model pop for approving leave or reject --}}
                                        @if ($leave->status === 'pending')
                                            <div class="d-flex justify-content-end gap-2">
                                                <x-system.modal-button class="btn btn-primary" text="Reject / Approve Leave" id="approveLeave" />
                                                <x-system.modal id="approveLeave" form="approveRejectForm">
                                                    <form id="approveRejectForm" method="POST"
                                                        action="{{ route('employee.leave.inspect', $leave->id) }}">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label for="doYou">Approve / Denie</label>
                                                            <select  class="form-control" name="status" aria-label="Default select example" required>
                                                                <option selected disabled value="">Choose from selection</option>
                                                                <option value="1">APPROVE</option>
                                                                <option value="0">DENY</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Comment</label>
                                                            <textarea class="form-control" name="comment" id="comment"
                                                            rows="4"></textarea>
                                                        </div>
                                                    </form>
                                                </x-system.modal>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>


                        @if ($leave->attachments)
                            @foreach ($leave->attachments as $attachment)

                                {{-- Here goes the new model --}}
                                <x-system.attachment-file-icon :path="$attachment->path" type="pdf" :attachmentName="$attachment->filename" />
                            @endforeach
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
