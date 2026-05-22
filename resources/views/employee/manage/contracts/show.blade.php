@extends('layouts.system')

@section('content')
    <div class="container py-4">
        <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <a href="{{ route('employee.manage.contracts.index') }}" class="btn btn-light btn-sm">
                <i class="mdi mdi-arrow-left"></i> Back to Contracts
            </a>
            @can('edit_contracts')
                @livewire('edit-contract-model', [
                    'contractId' => $contract->id,
                    'downloadRoute' => 'employee.manage.contracts.download',
                ])
            @endcan
        </div>

        @livewire('view-contract-view', [
            'contract' => $contract,
            'downloadRoute' => 'employee.manage.contracts.download',
        ])
    </div>
@endsection
