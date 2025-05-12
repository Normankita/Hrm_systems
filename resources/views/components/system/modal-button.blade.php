@props(['text'=>'', 'icon'=>'', 'id', 'textColor' => 'text-white'])

<button type="button"
    {{ $attributes->merge(
        ['class' => $attributes->has('class') ? $attributes->get('class') : 'btn btn-primary']) }}
    data-toggle="modal" data-target="#{{ $id }}">
    @if ($text)
        <span class="{{ $textColor }} text-capitalize">
            @if (Str::contains($text, '_'))
                {{ implode(' ', array_slice(explode('_', $text), 0, -1)) }}
            @else
                {{ $text }}
            @endif
        </span>
    @endif
    @if ($icon)
        <i class="{{$icon}}"></i>
    @endif
</button>
