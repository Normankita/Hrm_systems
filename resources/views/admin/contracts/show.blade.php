@extends('layouts.system')

@section('content')
    <!-- single ontract view -->
<div class="container py-5">
    <div class="mb-3">
    <!-- place to edit contract after creation -->
    @livewire('edit-contract-model')
    </div>

    @livewire('view-contract-view', ['contract' => $contract])

</div>
@endsection
