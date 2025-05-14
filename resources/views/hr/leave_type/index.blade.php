@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Leave Types</h4>
                </div>
                <div class="card-body">
                    <!-- create a create button right here -->
                    <x-system.modal-button class="btn btn-primary mb-3" id="createLeaveType" text="Create Leave Type" />
                    <x-system.modal size="modal-lg" id="createLeaveType" title="Create Leave Type"
                        form="createLeaveTypeForm">
                        <form id="createLeaveTypeForm" action="{{ route('hr.leave.type.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name">Leave Type Name</label>
                                    <input type="text" value="{{ old('name') }}" class="form-control" name="name"
                                        id="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="deducts_from_annual_leave">Is Annual Deducted</label>
                                    <select class="form-control" name="deducts_from_annual_leave"
                                        id="deducts_from_annual_leave">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="description">Leave Type description</label>
                                    <textarea class="form-control" name="description">
                                        {{ old('description') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="required_approval">Require Approval</label>
                                    <select class="form-control" name="required_approval" id="required_approval">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="eligibility_criteria">Eligibility Criteria</label>
                                    <textarea class="form-control" name="eligibility_criteria">
                                        {{ old('eligibility_criteria') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="is_compensated">Is Compensated</label>
                                    <select class="form-control" name="is_compensated" id="is_compensated">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </x-system.modal>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="leaveTypeTable">
                            <thead>
                                <tr>
                                    <th>Leave Type</th>
                                    <th>Deducts</th>
                                    <th>Require Approval</th>
                                    <th>Eligibility Criteria</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaveTypes as $leaveType)
                                    <tr>
                                        <td>{{ $leaveType->name }}</td>
                                        <td>
                                            {{ $leaveType->deducts_from_annual_leave ? 'yes' : 'no' }}
                                        </td>
                                        <td>
                                            {{ $leaveType->required_approval ? 'yes' : 'no' }}
                                        </td>
                                        <td>
                                            {{ $leaveType->eligibility_criteria }}
                                        </td>
                                        <td>
                                            <x-system.modal-button class="btn-sm btn-primary"
                                                id="updateLeaveType-{{ $leaveType->id }}" text="Edit" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($leaveTypes as $leaveType)
        <x-system.modal id="updateLeaveType-{{ $leaveType->id }}" title="Update Leave Type"
            form="updateLeaveTypeForm-{{ $leaveType->id }}">
            <form id="updateLeaveTypeForm-{{ $leaveType->id }}"
                action="{{ route('hr.leave.type.update', $leaveType->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Leave Type</label>
                        <input type="text" class="form-control" name="name" id="name"
                            value="{{ $leaveType->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="deducts_from_annual_leave">Is Annual Deducted</label>
                        <select class="form-control" name="deducts_from_annual_leave" id="deducts_from_annual_leave">
                            <option value="1" {{ $leaveType->deducts_from_annual_leave == 'true' ? 'selected' : '' }}>
                                Yes</option>
                            <option value="0"
                                {{ !$leaveType->deducts_from_annual_leave == 'false' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="days">Leave Type description</label>
                        <textarea class="form-control" name="description">{{ $leaveType->description }}</textarea>
                    </div>
                </div>
            </form>
        </x-system.modal>
    @endforeach
@endsection
