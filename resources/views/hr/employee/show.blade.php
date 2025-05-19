@extends('layouts.system')

@section('content')

<x-system.displays.employee-profile
:employee="$employee"
prefix="hr.employees"
:attachments="$attachments"

/>


@endsection