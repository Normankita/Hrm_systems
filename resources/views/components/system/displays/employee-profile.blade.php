
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
    </style>
@endsection

@props([
    'prefix'=>null,
    'employee',
])

<div class="container mt-4">
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-white">
                <i class="bi bi-person-circle me-2"></i>
                Employee Profile - {{ $employee->full_name }}
            </h4>
        </div>
        <div class="card-body">

            <div class="d-flex align-items-center mb-4">
                <img src="{{ $employee->profile_picture
                    ? asset('storage/' . $employee->profile_picture)
                    : 'https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg' }}"
                    alt="Profile Image" class="profile-img me-3">
                <div>
                    <h2 class="mb-0">{{ $employee->full_name }}</h2>
                    <p class="text-muted">{{ $employee->employee_type }}</p>

                    <x-system.modal-button class="btn btn-primary btn-custom me-2" data-bs-toggle="modal"
                        id="UpdateProfilePhoto" text="Update Profile Image" />

                    <x-system.modal id="UpdateProfilePhoto" form="updateProfilePhotoForm" title="New Profile photo">
                        <form action="{{ route($prefix.'.updateProfilePhoto', $employee->id)}}" id="updateProfilePhotoForm" enctype="multipart/form-data" method="POST">
                            @csrf
                            <div class="form-group">
                                <div class="col-md-12 mb-4">
                                    <x-system.form-inputs.file-upload
                                        name="profile_picture"
                                        label="Profile Picture"
                                        accept="image/jpeg,image/png,image/jpg"
                                        maxSize="2"
                                        icon="mdi-camera"
                                        col="12"
                                        required
                                    />
                                </div>
                            </div>
                        </form>
                    </x-system.modal>

                    @hasanyrole(['ADMIN', 'HR_OFFICER'])
                        <a href="{{ route($prefix.'.index') }}" class="btn btn-outline-secondary btn-custom">BACK TO LIST</a>
                    @endhasanyrole
                </div>
            </div>

            <div class="mb-4">
                <h4 class="section-title">Personal Information</h4>
                <div class="info-grid">
                    <div><strong>Email:</strong> {{ $employee->email }}</div>
                    <div><strong>Phone Number:</strong> {{ $employee->phone_number }}</div>
                    <div><strong>Date of Birth:</strong> {{ $employee->date_of_birth }}</div>
                    <div><strong>Gender:</strong> {{ $employee->gender }}</div>
                    <div><strong>Marital Status:</strong> {{ $employee->marital_status }}</div>
                    <div><strong>Residential Address:</strong> {{ $employee->residential_address }}</div>
                </div>
            </div>

            <div class="mb-4">
                <h4 class="section-title">Employment Details</h4>
                <div class="info-grid">
                    <div><strong>Company:</strong> {{ $employee->company->name }}</div>
                    <div><strong>Department:</strong> {{ $employee->department->name }}</div>
                    <div><strong>Date of Hire:</strong> {{ $employee->date_of_hire }}</div>
                    <div><strong>Date of Termination:</strong> {{ $employee->date_of_termination ?? 'N/A' }}</div>
                    <div><strong>Salary:</strong> {{ number_format($employee->salary, 2) }} Tshs</div>
                </div>
            </div>

            <div>
                <h4 class="section-title">Other Information</h4>
                <div class="info-grid">
                    <div><strong>National ID:</strong> {{ $employee->national_id }}</div>
                    <div><strong>TIN Number:</strong> {{ $employee->tin_number }}</div>
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route($prefix.'.edit', $employee->id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
            </div>
        </div>
    </div>
</div>
