@extends('layouts.system')

@section('content')
    <div id="page">
        @can('view_leaveTypes')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Leave Types</h4>
                        </div>
                        <div class="card-body">
                            <!-- create a create button right here -->
                            @can('create_leaveType')
                                <x-system.modal-button class="btn btn-primary mb-3" id="createLeaveType" text="Create Leave Type" />
                                <x-system.modal size="modal-lg" id="createLeaveType" title="Create Leave Type"
                                    form="createLeaveTypeForm">
                                    <form id="createLeaveTypeForm" action="{{ route('admin.leave.type.store') }}"
                                        method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="name">Leave Type Name</label>
                                                <input type="text" value="{{ old('name') }}" class="form-control"
                                                    name="name" id="name" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="deducts_from_annual_leave">Is Annual Deducted</label>
                                                <select v-model="isDeducted" class="form-control" name="deducts_from_annual_leave"
                                                    id="deducts_from_annual_leave">
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="description">Leave Type description</label>
                                                <textarea class="form-control" name="description">{{ old('description') }}</textarea>
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
                                                <textarea class="form-control" name="eligibility_criteria">{{ old('eligibility_criteria') }}</textarea>
                                            </div>
                                            <div class="form-group" v-if="showCompensated">
                                                <label for="is_compensated">Is Compensated</label>
                                                <select class="form-control" name="is_compensated" id="is_compensated">
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </x-system.modal>
                            @endcan

                            <div class="table-responsive">
                                <table class="table table-sm dt-table table-bordered table-striped" id="leaveTypeTable">
                                    <thead>
                                        <tr>
                                            <td>#</td>
                                            <th>Leave Type</th>
                                            <th>Compensated</th>
                                            <th>Deducts</th>
                                            <th>Require Approval</th>
                                            <th>Eligibility Criteria</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leaveTypes as $key => $leaveType)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $leaveType->name }}</td>
                                                <td>{{ $leaveType->is_compensated ? 'yes' : 'No' }}</td>
                                                <td>
                                                    {{ $leaveType->deducts_from_annual_leave ? 'Yes' : 'No' }}
                                                </td>
                                                <td>
                                                    {{ $leaveType->required_approval ? 'Yes' : 'No' }}
                                                </td>
                                                <td>
                                                    {{ $leaveType->eligibility_criteria }}
                                                </td>
                                                <td>
                                                    @can('edit_leaveType')
                                                        <x-system.modal-button class="btn-sm p-1 btn-primary"
                                                            id="updateLeaveType-{{ $leaveType->id }}" text="Edit" />
                                                    @endcan
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

            @can('edit_leaveType')
                @foreach ($leaveTypes as $key => $leaveType)
                    <x-system.modal id="updateLeaveType-{{ $leaveType->id }}" title="Update Leave Type"
                        form="updateLeaveTypeForm-{{ $leaveType->id }}">
                        <form id="updateLeaveTypeForm-{{ $leaveType->id }}"
                            action="{{ route('admin.leave.type.update', $leaveType->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name">Leave Type</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="{{ $leaveType->name }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="is_compensated">Is Compensated</label>
                                    <select class="form-control comp" data-comp="{{ $key }}" name="is_compensated"
                                        id="is_compensated">
                                        <option value="1" {{ $leaveType->is_compensated == 'true' ? 'selected' : '' }}>
                                            Yes</option>
                                        <option value="0" {{ !$leaveType->is_compensated == 'false' ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group deduct-{{ $key }}">
                                    <label for="deducts_from_annual_leave">Is Annual Deducted</label>
                                    <select class="form-control" name="deducts_from_annual_leave" id="deducts_from_annual_leave">
                                        <option value="1"
                                            {{ $leaveType->deducts_from_annual_leave == 'true' ? 'selected' : '' }}>
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
            @endcan
        @endcan
    </div>
@endsection

@section('scripts')
    <script>
        let checkingFields = function(e) {
            let comp = $(e).data('comp');
            if ($(e).val() == '0') {
                $('.deduct-' + comp).show();
            } else {
                $('.deduct-' + comp).hide();
                let deduct = $('.deduct-' + comp).find('select');
                deduct.val('0');
            }
        }
        $(document).ready(function() {
            $('.comp').on('change', function() {
                checkingFields(this);
            });
            $('.comp').each(function() {
                checkingFields(this);
            })
        });
        const app = Vue.createApp({
            data() {
                return {
                    isDeducted: "0",
                };
            },
            mounted() {
            },
            methods: {

            },
            computed: {
                showCompensated() {
                    return this.isDeducted == "1" ? false : true;
                }
            }
        });
        app.mount('#page');
    </script>
@endsection
