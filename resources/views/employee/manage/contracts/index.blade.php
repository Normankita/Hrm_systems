@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-1">Manage Employee Contracts</h4>
                    <p class="text-muted small mb-3">Create and manage employment contracts for employees in your company.</p>
                    <hr>
                    @can('edit_contracts')
                        @livewire('create-contract-model')
                    @endcan
                    @livewire('contract-table', ['showRoute' => 'employee.manage.contracts.show'])
                </div>
            </div>
        </div>
    </div>
@endsection
