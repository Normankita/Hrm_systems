@extends('layouts.system')

@section('content')
    @can('view_loans')
        <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- loan Details Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="card-title mb-1">Loan Details</h4>
                                <p class="text-muted">Review @can('edit_loans')and manage @endcan loan request</p>
                            </div>
                            <div>
                                <a href="{{ route('employee.manage.loans.index') }}" class="btn btn-light">
                                    <i class="mdi mdi-arrow-left me-1"></i> Back to List
                                </a>
                            </div>
                        </div>

                        <!-- loan Information -->
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
                                                <p class="mb-0">{{ $loan->employee->full_name }}</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-4">
                                                <p class="text-muted mb-0">Department</p>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="mb-0">{{ $loan->employee->department->name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">loan Details</h5>
                                        <div class="row mb-3">
                                            <div class="col-sm-4">
                                                <p class="text-muted mb-0">loan Type</p>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="mb-0">{{ $loan->loan_type }}</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-4">
                                                <p class="text-muted mb-0">Remarks</p>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="mb-0">{{ $loan->remarks }}</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-4">
                                                <p class="text-muted mb-0">Date Issued</p>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="mb-0">{{ $loan->issued_date }}</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-4">
                                                <p class="text-muted mb-0">Months to be paid</p>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="mb-0">{{ $loan->months_to_pay }}</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-4">
                                                <p class="text-muted mb-0">Status</p>
                                            </div>
                                            <div class="col-sm-8">
                                                <span
                                                    class="badge bg-{{ $loan->status === 'approved' ? 'success' : ($loan->status === 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($loan->status) }}
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
                                        <h5 class="card-title mb-3">loan Reason</h5>
                                        <p class="mb-4">{{ $loan->reason }}</p>
                                        {{-- model pop for approving loan or reject --}}
                                       @can('edit_loans')
                                            @if ($loan->status === 'pending')
                                            <div class="d-flex justify-content-end gap-2">
                                                <x-system.modal-button class="btn btn-primary" text="Reject / Approve loan" id="approveloan" />
                                                <x-system.modal id="approveloan" form="approveRejectForm">
                                                    <form id="approveRejectForm" method="POST"
                                                        action="{{ route('employee.manage.loans.inspect', $loan->id) }}">
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
                                       @endcan
                                    </div>
                                </div>
                            </div>
                        </div>


                        @if ($loan->attachments)
                            @foreach ($loan->attachments as $attachment)

                                {{-- Here goes the new model --}}
                                <x-system.attachment-file-icon :path="$attachment->path" type="pdf" :attachmentName="$attachment->filename" />
                            @endforeach
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
@endsection
