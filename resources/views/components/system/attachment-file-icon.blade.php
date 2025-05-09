@props(['attachmentName' => null, 'path', 'type'])

<div>
    @php
        $id = str_replace('.', '_', $attachmentName);
    @endphp
    <div class="input-group mb-1 w-100">
        <div class="input-group-text w-100 bg-light">
            <x-system.modal-button
                class="mdi {{ $type == 'image' ? 'mdi-file-image' : 'mdi-file-pdf text-danger' }} text-primary mdi-24px me-2"
                textColor="text-dark"
                data-bs-toggle="modal" id="{{ $id }}" :text="$attachmentName" />
        </div>
    </div>
    <x-system.modal size="modal-fullscreen" id="{{ $id }}" title="{{ $attachmentName }}">
        @if (str_contains($type, 'image'))
            <img style="height: 100vh; width: 100%; object-fit: contain;"
            src="{{ asset('storage/' . $path) }}"
                alt="{{ $attachmentName }}">
        @else
            <embed style="height: 100vh; width: 100%;" src="{{ asset('storage/' . $path) }}" type="application/pdf">
        @endif
    </x-system.modal>
</div>
