@extends('layouts.system')

@section('_links')
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
                    <img src="https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg" alt="Profile Image" class="profile-img me-3">
                    <div>
                        <h2 class="mb-0"><?php echo htmlspecialchars($employee->full_name); ?></h2>
                        <p class="text-muted"><?php echo htmlspecialchars($employee->employee_type); ?></p>
                        <button class="btn btn-primary btn-custom me-2" data-bs-toggle="modal"
                            data-bs-target="#uploadImageModal">Update Profile Image</button>
                        <a href="{{ route('admin.employees.index', $employee->id) }}" class="btn btn-outline-secondary btn-custom">BACK TO LIST</a>
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
                    <a href="{{ route('admin.employees.edit', $employee->id) }}"
                        class="btn btn-primary">
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
    </script>
</body>

</html>
