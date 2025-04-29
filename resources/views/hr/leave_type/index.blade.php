@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Leave Types</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="leaveTypeTable">
                            <thead>
                                <tr>
                                    <th>Leave Type</th>
                                    <th>Leave Days</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaveTypes as $leaveType)
                                    <tr>
                                        <td>{{ $leaveType->name }}</td>
                                        <td>
                                            {{ $leaveType->deducts_from_annual_leave ? 'yes' : "no" }}
                                        </td>
                                        <td>
                                            <x-system.modal-button  class="btn-sm btn-primary" id="updateLeaveType-{{$leaveType->id}}" text="Edit" />
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
    <x-system.modal id="updateLeaveType-{{$leaveType->id}}"
        title="Update Leave Type" form="updateLeaveTypeForm-{{$leaveType->id}}">
        <form id="updateLeaveTypeForm-{{$leaveType->id}}" action="{{ route('hr.leave.type.update', $leaveType->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label for="name">Leave Type</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $leaveType->name }}" required>
                </div>
                <div class="form-group">
                    <label for="deducts_from_annual_leave">Is Annual Deducted</label>
                    <select class="form-control" name="deducts_from_annual_leave" id="deducts_from_annual_leave">
                        <option value="1" {{ $leaveType->deducts_from_annual_leave == 'true' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ !$leaveType->deducts_from_annual_leave == 'false' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="days">Leave Type description</label>
                    <textarea class="form-control" name="description" >{{ $leaveType->description }}</textarea>
                </div>
            </div>
        </form>
    </x-system.modal>
    @endforeach
@endsection
