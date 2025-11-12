@extends('layouts.system')

@section('content')
    <div class="row justify-content-start">
        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-sm-12 col-md-12">
                        <!-- sending to back page -->
                        <x-back-button :route="route('employee.manage.disbursements.index')"></x-back-button>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <h2 class="card-title lead" style="text-transform: capitalize;">
                           {{ $basedOn }} Disbursement Allowance
                        </h2>
                    </div>
                    <table class="table table-bordered table-hover dt-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Allowance</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($disbursements as $disbursement)
                                @php
                                    $disbursement = (object) $disbursement;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $disbursement->employee->full_name }}</td>
                                    <td>{{ $disbursement->allowance->name }}</td>
                                    <td>{{ $disbursement->amount }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Total:</th>
                                <th id="total-amount" class="text-left">
                                    {{ number_format(array_sum(array_column($disbursements, 'amount'))) }}/=
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
