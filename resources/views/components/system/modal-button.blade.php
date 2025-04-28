@props(['text', 'id'])

<button type="button"
    {{ $attributes->merge(
        ['class' => $attributes->has('class') ? $attributes->get('class') : 'btn btn-primary']) }}
    data-toggle="modal" data-target="#{{ $id }}">
    {{ $text }}
</button>
