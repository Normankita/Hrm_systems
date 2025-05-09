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
                                    <th>Action</th>
                                    <th>Attachments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $key => $employee)
                                    @foreach ($employee->leaves as $index => $leave)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $leave->employee->full_name }}</td>
                                            <td>{{ $leave->leaveType->name }}</td>
                                            <td>{{ $leave->start_date }}</td>
                                            <td>{{ $leave->end_date }}</td>
                                            <td>
                                                <span class="badge">
                                                    {{ $leave->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <x-system.btn-view :route="route('hr.leave.show', $leave)" :key="$key . '_' . $index" />
                                            </td>
                                            <td>
                                                @if ($leave->attachments)
                                                    @foreach ($leave->attachments as $attachment)
                                                        <x-system.modal-button class="btn btn-primary py-1 px-2 text-lg"
                                                            data-bs-toggle="modal" id="DisplayAttachment{{$attachment->id}}"
                                                             icon="mdi mdi-paperclip" />
                                                            <x-system.modal size="modal-lg" id="DisplayAttachment{{$attachment->id}}"  title="{{$attachment->filename}}">
                                                                <embed src="{{ asset('storage/' . $attachment->path) }}"
                                                                    width="100%" height="100%" type="application/pdf">
                                                            </x-system.modal>
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
