@extends('layouts.system')

@section('content')
@can('create_employees')
    <div class="col-sm-12 col-md-12">
    <x-system.forms.create-employee-form prefix="employee.manage.employees."  />
</div>
@endcan
@endsection
