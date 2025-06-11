@props(['employees', 'class' => '', 'id' => null])
@php
    $pdfId = $id != null ? "$id-pdf" : '';
    $excelId = $id != null ? "$id-excel" : '';
@endphp
<div id="{{ $pdfId }}">
    <div class="container mr-4 mt-4">
        {{ $slot }}
        <table class="table table-bordered mt-5 {{ $class }}" id="{{ $excelId }}">
            <thead>
                <tr>
                    <th>name</th>
                    <th>date of hire</th>
                    <th>date of termination</th>
                    <th>email</th>
                    <th>employee type</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->full_name }}</td>
                        <td>{{ $employee->date_of_hire ?? 'N/A' }}</td>
                        <td>{{ $employee->date_of_termination ?? 'N/A'}}</td>
                        <td>{{$employee->email}}</td>
                        <td>{{ $employee->employee_type }}</td>
                        {{-- <td>
                            <span class="badge badge-danger">
                                {{ $employee->status }}
                            </span>
                        </td> --}}
                        <td>{{ Str::limit($employee->reason, 30, '...')??'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
