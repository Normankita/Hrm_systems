@props(['employee', 'roles', 'route', 'internal_route'])


{{-- Update Employee Form --}}

<link href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css"
    rel="stylesheet"
     type="text/css" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<style>

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
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

<div class="card">
    <div class="card-body p-30">
        <form action="{{ route($route, $employee->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">

                {{-- First Name --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">First Name</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-account"></span>
                        <input type="text" name="first_name" class="form-control"
                            value="{{ old('first_name', $employee->first_name) }}" required>
                    </div>
                </div>

                {{-- Last Name --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Last Name</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-account"></span>
                        <input type="text" name="last_name" class="form-control"
                            value="{{ old('last_name', $employee->last_name) }}" required>
                    </div>
                </div>

                {{-- Gender --}}
                <div class="col-md-12 mb-4">
                    <label class="text-dark font-weight-medium">Gender</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-gender-male-female"></span>
                        <select name="gender" class="form-control" required>
                            <option value="Male"
                                {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female"
                                {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female
                            </option>
                            <option value="Other"
                                {{ old('gender', $employee->gender) == 'Other' ? 'selected' : '' }}>Other
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Date of Birth --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Date of Birth</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-calendar"></span>
                        <input type="date" name="date_of_birth" class="form-control"
                            value="{{ old('date_of_birth', $employee->date_of_birth) }}" required>
                    </div>
                </div>

                {{-- Email --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Email</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-email"></span>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $employee->email) }}" required>
                    </div>
                </div>

                {{-- Phone Number --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-phone"></span>
                        <input type="text" name="phone_number" class="form-control"
                            value="{{ old('phone_number', $employee->phone_number) }}" required>
                    </div>
                </div>

                {{-- National ID --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">National ID</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-card-account-details"></span>
                        <input type="text" name="national_id" class="form-control"
                            value="{{ old('national_id', $employee->national_id) }}" required>
                    </div>
                </div>

                {{-- TIN Number --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">TIN Number</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-currency-usd"></span>
                        <input type="text" name="tin_number" class="form-control"
                            value="{{ old('tin_number', $employee->tin_number) }}">
                    </div>
                </div>

                {{-- Marital Status --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Marital Status</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-heart"></span>
                        <input type="text" name="marital_status" class="form-control"
                            value="{{ old('marital_status', $employee->marital_status) }}">
                    </div>
                </div>

                {{-- Residential Address --}}
                <div class="col-md-12 mb-4">
                    <label class="text-dark font-weight-medium">Residential Address</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-home-map-marker"></span>
                        <input type="text" name="residential_address" class="form-control"
                            value="{{ old('residential_address', $employee->residential_address) }}">
                    </div>
                </div>

                {{-- Employee Type --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Employee Type</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-account-box-outline"></span>
                        <select name="employee_type" class="form-control" required>
                            <option value="Permanent"
                                {{ old('employee_type', $employee->employee_type) == 'Permanent' ? 'selected' : '' }}>
                                Permanent</option>
                            <option value="Contract"
                                {{ old('employee_type', $employee->employee_type) == 'Contract' ? 'selected' : '' }}>
                                Contract</option>
                            <option value="Probation"
                                {{ old('employee_type', $employee->employee_type) == 'Probation' ? 'selected' : '' }}>
                                Probation</option>
                        </select>
                    </div>
                </div>

                {{-- Date of Hire --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Date of Hire</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-calendar-check"></span>
                        <input type="date" name="date_of_hire" class="form-control"
                            value="{{ old('date_of_hire', $employee->date_of_hire) }}" required>
                    </div>
                </div>

                {{-- Department --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Department</label>
                    <select name="department_id" class="form-control" required>
                        @foreach (App\Models\Department::all() as $department)
                            <option value="{{ $department->id }}"
                                {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Role --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Designation (Role)</label>
                    <select name="role_id" class="form-control" required>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ old('role_id', $employee->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    {{-- Passport Photo --}}
                    <div class="col-md-6 mb-4">
                        <label class="text-dark font-weight-medium">Passport Photo</label>
                        <div class="upload-area" id="passportPhotoArea">
                            <div class="upload-area-content">
                                <i class="mdi mdi-camera mdi-24px mb-2"></i>
                                <span>Drag & drop passport photo here or click to upload</span>
                                <small class="d-block text-muted mt-1">Accepted formats: JPG, PNG, JPEG (Max: 2MB)</small>
                            </div>
                            <input type="file" name="passport_photo" class="file-input" accept="image/jpeg,image/png,image/jpg" data-max-size="2">
                            <div class="file-preview mt-2 d-none">
                                <div class="d-flex align-items-center" style="overflow: hidden;">
                                    <i class="mdi mdi-file-image mdi-24px text-primary me-2"></i>
                                    <span class="file-name"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TIN Document --}}
                    <div class="col-md-6 mb-4">
                        <label class="text-dark font-weight-medium">TIN Document</label>
                        <div class="upload-area" id="tinDocumentArea">
                            <div class="upload-area-content">
                                <i class="mdi mdi-file-document mdi-24px mb-2"></i>
                                <span>Drag & drop TIN document here or click to upload</span>
                                <small class="d-block text-muted mt-1">Accepted format: PDF (Max: 5MB)</small>
                            </div>
                            <input type="file" name="tin_document" class="file-input" accept="application/pdf" data-max-size="5">
                            <div class="file-preview mt-2 d-none">
                                <div class="d-flex align-items-center" style="overflow: hidden;">
                                    <i class="mdi mdi-file-pdf mdi-24px text-danger me-2"></i>
                                    <span class="file-name"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- National ID Document --}}
                    <div class="col-md-6 mb-4">
                        <label class="text-dark font-weight-medium">National ID Document</label>
                        <div class="upload-area" id="nationalIdArea">
                            <div class="upload-area-content">
                                <i class="mdi mdi-card-account-details mdi-24px mb-2"></i>
                                <span>Drag & drop National ID document here or click to upload</span>
                                <small class="d-block text-muted mt-1">Accepted format: PDF (Max: 5MB)</small>
                            </div>
                            <input type="file" name="national_id_document" class="file-input" accept="application/pdf" data-max-size="5">
                            <div class="file-preview mt-2 d-none">
                                <div class="d-flex align-items-center" style="overflow: hidden;">
                                    <i class="mdi mdi-file-pdf mdi-24px text-danger me-2">
                                    </i>
                                    <span class="file-name"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CV Document --}}
                    <div class="col-md-6 mb-4">
                        <label class="text-dark font-weight-medium">CV Document</label>
                        <div class="upload-area" id="cvArea">
                            <div class="upload-area-content">
                                <i class="mdi mdi-file-document-outline mdi-24px mb-2"></i>
                                <span>Drag & drop CV document here or click to upload</span>
                                <small class="d-block text-muted mt-1">Accepted format: PDF (Max: 5MB)</small>
                            </div>
                            <input type="file" name="cv_document" class="file-input" accept="application/pdf" data-max-size="5">
                            <div class="file-preview mt-2 d-none">
                                <div class="d-flex align-items-center" style="overflow: hidden;">
                                    <i class="mdi mdi-file-pdf mdi-24px text-danger me-2"></i>
                                    <span class="file-name"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Certificates --}}
                    <div class="col-md-12 mb-4">
                        <label class="text-dark font-weight-medium">Certificates</label>
                        <div class="upload-area" id="certificatesArea">
                            <div class="upload-area-content">
                                <i class="mdi mdi-certificate mdi-24px mb-2"></i>
                                <span>Drag & drop certificates here or click to upload</span>
                                <small class="d-block text-muted mt-1">Accepted format: PDF (Max: 5MB per file)</small>
                            </div>
                            <input type="file" name="certificates[]" class="file-input" accept="application/pdf" data-max-size="5" multiple>
                            <div class="file-preview mt-2 d-none">
                                <div class="certificates-list"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-between">
                        {{-- Submit Button on the left --}}
                        <button type="submit" class="btn btn-primary ">
                            <i class="mdi mdi-content-save-edit"></i> Update Employee
                        </button>
                    </div>
                </div>
                <div class="row mx-4 mt-4">
                     {{-- Change Password Button on the right --}}
                     <div class="col-12 d-flex justify-content-between">
                         <x-system.modal-button class="btn btn-secondary " text="Change Password"
                     id="UpdatePassword" />
                     </div>
                </div>
                
            </div>
        </form>

        {{-- Modal for Changing Password --}}
        <x-system.modal id="UpdatePassword" form="updatePasswordForm" title="ChangePassword">
            <form id="updatePasswordForm" method="POST"
                action="{{ route($internal_route, $employee->id) }}">
                @csrf
                <div class="form-group">
                    <label for="">New Password</label>
                    <input type="password" class="form-control" name="password" id="password"
                        placeholder="Enter new password">
                </div>
            </form>
        </x-system.modal>
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
    