@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-1">My Employee Relations</h4>
                    <p class="text-muted small mb-3">
                        View complaints, conflicts, disciplinary actions, and resolutions related to you.
                        You may file new complaints or report conflicts; supporting PDFs can be attached.
                    </p>
                    <hr>
                    @livewire('employee-relations-hub', [
                        'employeeId' => $employee->id,
                        'allowManage' => false,
                        'personalMode' => true,
                        'requirePermission' => false,
                        'downloadRoute' => 'employee.employee-relations.download',
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection
