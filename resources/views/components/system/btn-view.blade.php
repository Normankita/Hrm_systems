@props(['text' => 'view', 'route', 'class', 'textColor' => ''])

<a href="{{ $route }}"
    @class([
       "btn btn-outline-dark p-0 px-1 btn-sm mdi mdi-eye-outline $textColor",
       $class ?? false
   ])>
    {{ $text }} &nbsp </a>
