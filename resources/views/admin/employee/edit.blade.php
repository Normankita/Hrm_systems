@extends('layouts.system')

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-12">
    <x-system.forms.update-employee-form route="admin.employees.update" internal_route="admin.employees.update.password" :roles="$roles" :employee="$employee" />
    </div>
</div>
@endsection
