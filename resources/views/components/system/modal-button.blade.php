@props(['text'=>'', 'icon'=>'', 'id'])

<button type="button"
    {{ $attributes->merge(
        ['class' => $attributes->has('class') ? $attributes->get('class') : 'btn btn-primary']) }}
    data-toggle="modal" data-target="#{{ $id }}">
    @if ($text)
        {{$text}}
    @endif
    @if ($icon)
        <i class="{{$icon}}"></i>
    @endif
</button>
