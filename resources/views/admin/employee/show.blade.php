@extends('layouts.system')

@section('content')
<x-system.displays.employee-profile
:employee="$employee"
prefix="admin.employees"
/>

@endsection