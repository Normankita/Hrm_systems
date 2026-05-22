@php
    use App\Http\Utils\Traits\CompanyTrait;
    $authUser = auth()->user();
@endphp

@extends('layouts.system')


@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-start">
                        <div class="col-md-12">
                            @if($authUser->can('ind_mark_attendance')
                               and CompanyTrait::isSetting('user_check_attendance', 'yes'))
                                @livewire('attend-model')
                            @endif
                        </div>
                    </div>
                    @livewire('user-attendance')
                </div>
            </div>
        </div>
    </div>
@endsection
