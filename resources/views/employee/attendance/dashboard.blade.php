@extends('layouts.system')


@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-start">
                        <div class="col-md-12">
                            @livewire('attend-model')
                        </div>
                    </div>
                    @livewire('user-attendance')
                </div>
            </div>
        </div>
    </div>
@endsection
