@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-1">My Contracts</h4>
                    <p class="text-muted small mb-3">View your employment contract details and download signed documents.</p>
                    <hr>
                    @livewire('contract-table', [
                        'employeeId' => $employee->id,
                        'showRoute' => 'employee.contracts.show',
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection
