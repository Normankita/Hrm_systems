@extends('layouts.system')

@section('_links')
    <link href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" rel="stylesheet" type="text/css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .profile-card {
            max-width: 800px;
            margin: 2rem auto;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            border-bottom: 2px solid #007bff;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .btn-custom {
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

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
@endsection

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0 text-white">
                    <i class="bi bi-person-circle me-2"></i>
                    Employee Profile - {{ $employee->first_name }} {{ $employee->last_name }}
                </h4>
            </div>
            <div class="card-body">

                <!-- Header with Profile Image -->
                <div class="d-flex align-items-center mb-4">
                    <img src="{{ $employee->profile_picture
                        ? asset('storage/attachments/employees/profile_photos/' . $employee->profile_picture)
                        : 'https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg' }}"
                        alt="Profile Image" class="profile-img me-3">
                    <div>

                        <h2 class="mb-0"><?php echo htmlspecialchars($employee->full_name); ?></h2>
                        <p class="text-muted"><?php echo htmlspecialchars($employee->employee_type); ?></p>
                        <x-system.modal-button class="btn btn-primary btn-custom me-2" data-bs-toggle="modal"
                            id="UpdateProfilePhoto" text="Update Profile Image" />
                        <x-system.modal id="UpdateProfilePhoto" form="updateProfilePhotoForm" title="New Profile photo">
                            <form action="{{ route('admin.employees.updateProfilePhoto', $employee->id) }}" id="updateProfilePhotoForm" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="form-group">
                                    <div class="col-md-12 mb-4">
                                        <label class="text-dark font-weight-medium">Passport Photo</label>
                                        <div class="upload-area" id="passportPhotoArea">
                                            <div class="upload-area-content">
                                                <i class="mdi mdi-camera mdi-24px mb-2"></i>
                                                <span>Drag & drop passport photo here or click to upload</span>
                                                <small class="d-block text-muted mt-1">Accepted formats: JPG, PNG, JPEG
                                                    (Max: 2MB)</small>
                                            </div>
                                            <input type="file" name="passport_photo" class="file-input"
                                                accept="image/jpeg,image/png,image/jpg" data-max-size="2">
                                            <div class="file-preview mt-2 d-none">
                                                <div class="d-flex align-items-center" style="overflow: hidden;">
                                                    <i class="mdi mdi-file-image mdi-24px text-primary me-2"></i>
                                                    <span class="file-name"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </x-system.modal>
                        <a href="{{ route('admin.employees.index', $employee->id) }}"
                            class="btn btn-outline-secondary btn-custom">BACK TO LIST</a>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="mb-4">
                    <h4 class="section-title">Personal Information</h4>
                    <div class="info-grid">
                        <div>
                            <strong>Email:</strong> <?php echo htmlspecialchars($employee->email); ?>
                        </div>
                        <div>
                            <strong>Phone Number:</strong> <?php echo htmlspecialchars($employee->phone_number); ?>
                        </div>
                        <div>
                            <strong>Date of Birth:</strong> <?php echo htmlspecialchars($employee->date_of_birth); ?>
                        </div>
                        <div>
                            <strong>Gender:</strong> <?php echo htmlspecialchars($employee->gender); ?>
                        </div>
                        <div>
                            <strong>Marital Status:</strong> <?php echo htmlspecialchars($employee->marital_status); ?>
                        </div>
                        <div>
                            <strong>Residential Address:</strong> <?php echo htmlspecialchars($employee->residential_address); ?>
                        </div>
                    </div>
                </div>

                <!-- Employment Details -->
                <div class="mb-4">
                    <h4 class="section-title">Employment Details</h4>
                    <div class="info-grid">
                        <div>
                            <strong>User ID:</strong> <?php echo htmlspecialchars($employee->user_id); ?>
                        </div>
                        <div>
                            <strong>Company ID:</strong> <?php echo htmlspecialchars($employee->company_id); ?>
                        </div>
                        <div>
                            <strong>Department ID:</strong> <?php echo htmlspecialchars($employee->department_id); ?>
                        </div>
                        <div>
                            <strong>Date of Hire:</strong> <?php echo htmlspecialchars($employee->date_of_hire); ?>
                        </div>
                        <div>
                            <strong>Date of Termination:</strong> <?php echo $employee->date_of_termination ? htmlspecialchars($employee->date_of_termination) : 'N/A'; ?>
                        </div>
                        <div>
                            <strong>Salary:</strong> <?php echo number_format($employee->salary, 2); ?> Tshs
                        </div>
                    </div>
                </div>

                <!-- Other Information -->
                <div>
                    <h4 class="section-title">Other Information</h4>
                    <div class="info-grid">
                        <div>
                            <strong>National ID:</strong> <?php echo htmlspecialchars($employee->national_id); ?>
                        </div>
                        <div>
                            <strong>TIN Number:</strong> <?php echo htmlspecialchars($employee->tin_number); ?>
                        </div>
                    </div>
                </div>

                <!-- Modal for Profile Image Upload -->
                <div class="modal fade" id="uploadImageModal" tabindex="-1" aria-labelledby="uploadImageModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="uploadImageModalLabel">Upload Profile Image</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="uploadImageForm" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="profileImage" class="form-label">Choose an image</label>
                                        <input type="file" class="form-control" id="profileImage" name="profileImage"
                                            accept="image/*" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-custom">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection







<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>

    </style>
</head>

<body>


    <!-- Bootstrap JS and Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script>
        document.getElementById('uploadImageForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Add your image upload logic here (e.g., AJAX call to server)
            alert('Image upload functionality to be implemented');
            bootstrap.Modal.getInstance(document.getElementById('uploadImageModal')).hide();
        });
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
</body>

</html>
