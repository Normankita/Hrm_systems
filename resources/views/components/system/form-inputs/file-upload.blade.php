<link href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" rel="stylesheet" type="text/css" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<style>
    .dropzone-container {
        position: relative;
    }

    .dropzone {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        background: #f8f9fa;
        min-height: 150px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .dropzone:hover {
        border-color: #4e73df;
        background: #f1f3f9;
    }

    .dropzone .dz-message {
        text-align: center;
        color: #6c757d;
    }

    .dropzone .dz-message i {
        color: #4e73df;
        font-size: 2rem;
    }

    .dropzone .dz-preview {
        margin: 10px;
    }

    .dropzone .dz-preview .dz-image {
        border-radius: 8px;
    }

    .dropzone .dz-preview .dz-details {
        color: #495057;
    }

    .dropzone .dz-preview .dz-remove {
        color: #dc3545;
    }

    .dropzone .dz-preview .dz-remove:hover {
        text-decoration: none;
        color: #bd2130;
    }

    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        background: #f8f9fa;
        min-height: 150px;
        padding: 20px;
        position: relative;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .upload-area:hover {
        border-color: #4e73df;
        background: #f1f3f9;
    }

    .upload-area-content {
        text-align: center;
        color: #6c757d;
    }

    .upload-area-content i {
        color: #4e73df;
        font-size: 2rem;
    }

    .file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .file-preview {
        background: #fff;
        border-radius: 4px;
        padding: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .certificates-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .certificate-item {
        display: flex;
        align-items: center;
        padding: 8px;
        background: #f8f9fa;
        border-radius: 4px;
    }
</style>

@props(['name', 'label', 'accept', 'maxSize', 'icon', 'multiple' => false,
 'col' => 6])

@php
    $inputId = Str::slug($name) . 'Area';
@endphp

<div class="col-md-{{ $col }} mb-4">
    <label class="text-dark font-weight-medium">{{ $label }} <span class="text-muted font-weight-lighter text-sm">(optional)</span></label>

    {{ $slot }}

    <div class="upload-area" id="{{ $inputId }}">
        <div class="upload-area-content">
            <i class="mdi {{ $icon }} mdi-24px mb-2"></i>
            <span>Drag & drop {{ strtolower($label) }} here or click to upload</span>
            <small class="d-block text-muted mt-1">Accepted format{{ $multiple ? 's' : '' }}: {{ $accept }} (Max:
                {{ $maxSize }}MB{{ $multiple ? ' per file' : '' }})</small>
        </div>
        <input type="file" name="{{ $name }}" class="file-input" accept="{{ $accept }}"
            data-max-size="{{ $maxSize }}" @if ($multiple) multiple @endif>
        <div class="file-preview mt-2 d-none">
            @if ($multiple)
                <div class="certificates-list"></div>
            @else
                <div class="d-flex align-items-center" style="overflow: hidden;">
                    <i
                        class="mdi mdi-file-{{ Str::contains($accept, 'image') ? 'image text-primary' : 'pdf text-danger' }} mdi-24px me-2"></i>
                    <span class="file-name"></span>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle file selection
        $('.file-input').on('change', function(e) {
            const file = e.target.files[0];
            const maxSize = $(this).data('max-size');
            const area = $(this).closest('.upload-area');
            const preview = area.find('.file-preview');

            if (file) {
                // Check file size
                if (file.size > maxSize * 1024 * 1024) {
                    alert(`File size must be less than ${maxSize}MB`);
                    return;
                }

                // Show preview
                preview.removeClass('d-none');

                if ($(this).attr('multiple')) {
                    // Handle multiple files (certificates)
                    const list = preview.find('.certificates-list');
                    list.empty();

                    Array.from(e.target.files).forEach(file => {
                        list.append(`
                            <div class="certificate-item">
                                <i class="mdi mdi-file-pdf mdi-24px text-danger me-2"></i>
                                <span>${file.name}</span>
                            </div>
                        `);
                    });
                } else {
                    // Handle single file
                    preview.find('.file-name').text(file.name);
                }
            }
        });

        // Handle drag and drop
        $('.upload-area').on('dragover', function(e) {
            e.preventDefault();
            $(this).addClass('border-primary');
        }).on('dragleave', function(e) {
            e.preventDefault();
            $(this).removeClass('border-primary');
        }).on('drop', function(e) {
            e.preventDefault();
            $(this).removeClass('border-primary');

            const input = $(this).find('.file-input');
            input.prop('files', e.originalEvent.dataTransfer.files);
            input.trigger('change');
        });

        // Handle file removal
        $(document).on('click', '.remove-file', function() {
            const area = $(this).closest('.upload-area');
            const input = area.find('.file-input');
            const preview = area.find('.file-preview');

            input.val('');
            preview.addClass('d-none');
            preview.find('.file-name').text('');
            preview.find('.certificates-list').empty();
        });
    });
</script>
