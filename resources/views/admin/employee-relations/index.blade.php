@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-1">Employee Relations</h4>
                    <p class="text-muted small mb-4">Manage complaints, disciplinary actions, workplace conflicts, and resolutions.</p>
                    <hr>
                    @livewire('employee-relations-hub')
                </div>
            </div>
        </div>
    </div>
@endsection
