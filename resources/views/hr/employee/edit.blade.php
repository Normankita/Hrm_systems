@extends('layouts.system')

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-12">
    <x-system.forms.update-employee-form route="hr.employees.update" internal_route="hr.employees.update.password" :roles="$roles" :employee="$employee" :pay_grades="$pay_grades" />
    </div>
</div>
@endsection
