@extends('layouts.system')

@section('content')
<x-system.displays.employee-profile
:employee="$employee"
:attachments="$attachments"
prefix="admin.employees"
/>

@endsection