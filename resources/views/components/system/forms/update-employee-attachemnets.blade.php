@props(['attachments' => null])

<div class="row">
    <x-system.form-inputs.file-upload name="passport_photo" label="Passport Photo"
        accept="image/jpeg,image/png,image/jpg" maxSize="2" icon="mdi-camera">
        @if ($attachments)
            @php
                $attachment = $attachments->where('type', 'passport_photo')->first();
                $attachmentName = $attachment ? $attachment->filename : null;
            @endphp
            @if ($attachmentName)
                <x-system.attachment-file-icon :path="$attachment->path" type="image" :attachmentName="$attachmentName" />
            @endif
        @endif
    </x-system.form-inputs.file-upload>


    <x-system.form-inputs.file-upload name="tin_document" label="TIN Document" accept="application/pdf" maxSize="5"
        icon="mdi-file-document">
        @if ($attachments)
            @php
                $attachment = $attachments->where('type', 'tin')->first();
                $attachmentName = $attachment ? $attachment->filename : null;
            @endphp
            @if ($attachmentName)
                <x-system.attachment-file-icon :path="$attachment->path" type="pdf" :attachmentName="$attachmentName" />
            @endif
        @endif
    </x-system.form-inputs.file-upload>


    <x-system.form-inputs.file-upload name="national_id_document" label="National ID Document" accept="application/pdf"
        maxSize="5" icon="mdi-card-account-details">
        @if ($attachments)
            @php
                $attachment = $attachments->where('type', 'national_id')->first();
                $attachmentName = $attachment ? $attachment->filename : null;
            @endphp
            @if ($attachmentName)
                <x-system.attachment-file-icon :path="$attachment->path" type="pdf" :attachmentName="$attachmentName" />
            @endif
        @endif
    </x-system.form-inputs.file-upload>

    <x-system.form-inputs.file-upload name="cv_document" label="CV Document" accept="application/pdf" maxSize="5"
        icon="mdi-file-document-outline">
        @if ($attachments)
            @php
                $attachment = $attachments->where('type', 'cv')->first();
                $attachmentName = $attachment ? $attachment->filename : null;
            @endphp
            @if ($attachmentName)
                <x-system.attachment-file-icon :path="$attachment->path" type="pdf" :attachmentName="$attachmentName" />
            @endif
        @endif
    </x-system.form-inputs.file-upload>

    <x-system.form-inputs.file-upload name="letter" label="Local government letter" accept="application/pdf" maxSize="5"
    col="12" icon="mdi-file-document-outline">
    @if ($attachments)
        @php
            $attachment = $attachments->where('type', 'letter')->first();
            $attachmentName = $attachment ? $attachment->filename : null;
        @endphp
        @if ($attachmentName)
            <x-system.attachment-file-icon :path="$attachment->path" type="pdf" :attachmentName="$attachmentName" />
        @endif
    @endif
</x-system.form-inputs.file-upload>

    <x-system.form-inputs.file-upload name="certificates[]" label="Certificates" accept="application/pdf" maxSize="5"
        col="12" icon="mdi-certificate" multiple>
        @if ($attachments)
            @php
                $attachments = $attachments->where('type', 'certificate');
            @endphp
            @foreach ($attachments as $attachment)
                @if ($attachment->filename)
                    <x-system.attachment-file-icon :path="$attachment->path" type="pdf" :attachmentName="$attachment->filename" />
                @endif
            @endforeach
        @endif
    </x-system.form-inputs.file-upload>
</div>