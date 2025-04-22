@extends('layouts.system')

@section('content')
    @role('ADMIN')
        @include('admin.dashboard')
    @endrole>
@endsection
