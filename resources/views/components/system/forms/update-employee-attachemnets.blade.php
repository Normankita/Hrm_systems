<div class="row">
    <x-system.form-inputs.file-upload name="passport_photo"
    label="Passport Photo" accept="image/jpeg,image/png,image/jpg"
        maxSize="2" icon="mdi-camera" />

    <x-system.form-inputs.file-upload name="tin_document"
    label="TIN Document" accept="application/pdf" maxSize="5"
        icon="mdi-file-document" />

    <x-system.form-inputs.file-upload name="national_id_document"
    label="National ID Document" accept="application/pdf"
        maxSize="5" icon="mdi-card-account-details" />

    <x-system.form-inputs.file-upload name="cv_document" label="CV Document" accept="application/pdf" maxSize="5"
        icon="mdi-file-document-outline" />

    <x-system.form-inputs.file-upload name="certificates[]"
    label="Certificates" accept="application/pdf" maxSize="5" col="12"
        icon="mdi-certificate" multiple />
</div>
