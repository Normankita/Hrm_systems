@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-1">Manage Employee Relations</h4>
                    <p class="text-muted small mb-3">
                        Register and manage complaints, conflicts, disciplinary actions, and resolutions for employees in your company.
                    </p>
                    <hr>
                    @livewire('employee-relations-hub', [
                        'allowManage' => true,
                        'personalMode' => false,
                        'requirePermission' => true,
                        'downloadRoute' => 'employee.manage.employee-relations.download',
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection
