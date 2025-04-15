@extends("layouts.system")

@section('content')
    <div class="card card-default">
        <div class="px-6 py-4">
            <p>Mono provides a variety of <span class="text-secondary text-capitalize"> Bootstrap Basic
                    inputs </span> components with a
                little customization that suits its design standards. For more information, please see
                the official <a class="font-weight-bold"
                    href="https://getbootstrap.com/docs/4.3/components/forms/" target="_blank">
                    Bootstrap documentation.</a></p>
            <x-system.input name="gender" placeholder="Enter your name" />
        </div>
    </div>
@endsection
