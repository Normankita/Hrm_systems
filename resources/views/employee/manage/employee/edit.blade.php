@extends('layouts.system')

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-12">
    <x-system.forms.update-employee-form route="employee.manage.employees.update" internal_route="employee.manage.employees.update.password"  :employee="$employee" />
    </div>
</div>
@endsection
