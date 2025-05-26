@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-12">
            <x-system.tables.payroll-table-card :payrolls="$payrolls" title='Rejected'/>
        </div>
    </div>
@endsection
