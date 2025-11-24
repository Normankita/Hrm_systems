@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <x-back-button :route="route('employee.manage.disbursements.index')"></x-back-button>
        </div>
    </div>
    <div class="row justify-content-start">
        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="dt-table table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Allowance</th>
                                <th>Group</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($disbursements as $disbursement)
                                @php
                                    @$disbursement = (object) $disbursement;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $disbursement->employee->full_name }}</td>
                                    <td>{{ $disbursement->allowance->name }}
                                    <td>{{ $disbursement->group->name }}</td>
                                    <td>{{ number_format($disbursement->amount) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
