@props(['text' => 'view', 'route', 'class'])

<a href="{{ $route }}" class="{{ $class ??
    'btn btn-outline-dark p-1  btn-sm mdi
mdi-eye-outline' }}">
    {{ $text }} &nbsp </a>
