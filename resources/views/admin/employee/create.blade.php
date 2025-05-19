@extends('layouts.system')

@section('content')
<div class="col-sm-12 col-md-12">
    <x-system.forms.create-employee-form route="admin.employees.store"
     />
</div>
@endsection
