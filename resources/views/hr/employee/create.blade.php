@extends('layouts.system')

@section('content')
<div class="col-sm-12 col-md-12">
    <x-system.forms.create-employee-form route="hr.employees.store"  />
</div>
@endsection
