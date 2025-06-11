@props(['leaves', 'class' => '', 'id' => null])
@php
    $pdfId = $id != null ? "$id-pdf" : '';
    $excelId = $id != null ? "$id-excel" : '';
@endphp
<div id="{{ $pdfId }}">
    <div class="container mr-4 mt-4">
        {{ $slot }}
        <table class="table mt-5 {{ $class }}" id="{{ $excelId }}">
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

                @php $co = 0; @endphp
                @while (true)
                    @if ($co > 100)
                        @break
                    @endif
                    @php $co++; @endphp
                    <tr>
                        <td>Annual Leave</td>
                        <td>2025-06-01</td>
                        <td>2025-06-10</td>
                        <td>Approved</td>
                        <td>Family Vacation</td>
                    </tr>

                @endwhile
            </tbody>
        </table>
    </div>
</div>
