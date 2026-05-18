@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Yealy Employee Contracts</h4>
                    <hr>
                    @livewire('create-contract-model')
                    @livewire('contract-table')
                </div>
            </div>
        </div>
    </div>
@endsection
