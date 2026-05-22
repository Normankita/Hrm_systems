@extends('layouts.system')

@section('content')
    <div class="container py-4">
        <div class="mb-3">
            <a href="{{ route('employee.contracts.index') }}" class="btn btn-light btn-sm">
                <i class="mdi mdi-arrow-left"></i> Back to My Contracts
            </a>
        </div>

        @livewire('view-contract-view', [
            'contract' => $contract,
            'downloadRoute' => 'employee.contracts.download',
        ])
    </div>
@endsection
