@props(['leaves', 'class' => '', 'id' =>  null])
@php
        $pdfId = $id != null ? "$id-pdf" : '';
        $excelId =  $id != null ? "$id-excel" : '';
@endphp
<div id="{{ $pdfId }}">
    <table class="table table-bordered mt-5 {{ $class }}" id="{{ $excelId }}">
        <thead>
            <tr>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaves as $leave)
                <tr>
                    <td>{{ $leave->leave_type }}</td>
                    <td>{{ $leave->start_date->format('Y-m-d') }}</td>
                    <td>{{ $leave->end_date->format('Y-m-d') }}</td>
                    <td><span class="badge badge-danger">
                            {{ $leave->status }}
                        </span></td>
                    <!-- adjust a reason to contain 30 characters -->
                    <td>{{ Str::limit($leave->reason, 30, '...') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
