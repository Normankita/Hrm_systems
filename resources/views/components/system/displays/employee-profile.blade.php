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
    'prefix' => null,
    'employee',
    'attachments',
    'pay_grades',
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

            <div canss="d-flex align-items-center mb-4">
                <img src="{{ $employee->profile_picture
                    ? asset('storage/' . $employee->profile_picture)
                    : 'https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg' }}"
                    alt="Profile Image" class="profile-img me-3">
                <div>
                    <h2 class="mb-0">{{ $employee->full_name }}</h2>
                    <span class="lead">Registered AS: <b>
                        {{ $employee->user->roles->where('name', '!=', 'EMPLOYEE')->first()->name }}
                    </b></span>
                    <p class="text-muted"><span>{{ $employee->employee_type }}</span><span> | </span>
                        <span>
                            {{ $employee->pay_grades->where('pivot.status', true)->first()?->name ?? 'No Active Paygrade' }}
                        </span>
                    </p>

                    @hasanyrole(['ADMIN', 'HR_OFFICER'])
                        <x-system.modal-button class="btn btn-primary btn-custom me-2" data-bs-toggle="modal"
                            id="UpdateProfilePhoto" text="Update Profile Image" />

                        <x-system.modal id="UpdateProfilePhoto" form="updateProfilePhotoForm" title="New Profile photo">
                            <form action="{{ route($prefix . '.updateProfilePhoto', $employee->id) }}"
                                id="updateProfilePhotoForm" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="form-group">
                                    <div class="col-md-12 mb-4">
                                        <x-system.form-inputs.file-upload name="profile_picture" label="Profile Picture"
                                            accept="image/jpeg,image/png,image/jpg" maxSize="2" icon="mdi-camera"
                                            col="12" required />
                                    </div>
                                </div>
                            </form>
                        </x-system.modal>
                    @endhasanyrole
                    @canany(['edit_employees', 'edit_paygrade'])
                        
                        <x-system.modal-button class="btn btn-primary btn-custom me-2" data-bs-toggle="modal"
                            id="UpdatePayGrade" text="Update PayGrade" />

                        <x-system.modal id="UpdatePayGrade" form="UpdatePayGradeForm" title="Update PayGrade">
                            <form action="{{ route('payroll.employees.UpdatePayGrade', $employee) }}"
                                id="UpdatePayGradeForm" enctype="multipart/form-data" method="POST">

                                @csrf
                                @method('PATCH')

                                <div class="form-group row">
                                    <div class="col-md-6 mb-4">
                                        <label for="pay_grade_id" class="text-dark font-weight-medium">PayGrade</label>
                                        <select name="pay_grade_id" id="pay_grade_id" class="form-control" required>
                                            <option value="" disabled
                                                {{ !old('pay_grade_id') && !optional($employee->pay_grades->firstWhere('pivot.status', true)) ? 'selected' : '' }}>
                                                Select PayGrade</option>
                                            @foreach ($pay_grades as $pay_grade)
                                                <option value="{{ $pay_grade->id }}"
                                                    {{ old('pay_grade_id', optional($employee->pay_grades->firstWhere('pivot.status', true))->id) == $pay_grade->id ? 'selected' : '' }}>
                                                    {{ $pay_grade->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Effective from date --}}
                                    <div class="col-md-6 mb-4">
                                        <label class="text-dark font-weight-medium">Effective From</label>
                                        <div class="input-group">
                                            <span class="input-group-text mdi mdi-calendar"></span>
                                            <input type="date" name="effective_from" class="form-control"
                                                value="{{ old('effective_from', optional($employee->pay_grades->firstWhere('pivot.status', true))->pivot->effective_from ?? '') }}"
                                                required>
                                        </div>
                                        @error('effective_from')
                                            <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    {{-- Salary --}}
                                    <div class="col-md-12 mb-4">
                                        <label class="text-dark font-weight-medium">Base Salary Override <span
                                                class="text-muted font-weight-lighter text-sm">(optional)</span> </label>
                                        <div class="input-group">
                                            <span class="input-group-text mdi mdi-cash-multiple"></span>
                                            <input type="number" name="base_salary_override" class="form-control"
                                                placeholder="e.g., 1200000"
                                                value="{{ old('base_salary_override', optional($employee->pay_grades->firstWhere('pivot.status', true))->pivot->base_salary_override ?? '') }}">
                                        </div>
                                        @error('base_salary_override')
                                            <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </form>
                        </x-system.modal>
                    @endcanany
                    @canany(['create_deductions','view_deductions','edit_deductions' ])
                        <x-system.modal id="ManageDeductions" form="addDeductionForm" title="Manage Employee Deductions">
                            {{-- Existing Deductions Table --}}
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Deduction</th>
                                            <th>Amount</th>
                                            <th>Cycles</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($employee->deductions as $deduction)
                                            <tr>
                                                <td>{{ $deduction->name }}</td>
                                                <td>{{ number_format($deduction->total_amount, 2) }}</td>
                                                <td>{{ $deduction->installments }}</td>
                                                <td>
                                                    <form
                                                        action="{{ route('hr.deductions.destroy', [$employee->id, $deduction->id]) }}"
                                                        method="POST" onsubmit="return confirm('Delete this deduction?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger btn-sm p-1 mx-1 mdi mdi-trash">Delete</button>
                                                    </form>

                                                    <!-- Edit button -->
                                                    @can('edit_deductions')
                                                        <x-system.modal-button
                                                        class="btn btn-outline-dark btn-sm p-1 m-1 mdi mdi-pencil"
                                                        id="editDeductionModal-{{ $deduction->id }}" data-bs-toggle="modal"
                                                        text="Edit" textColor="" />
                                                    @endcan

                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No deductions found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                @foreach ($employee->deductions as $deduction)
                                    @can('edit_deductions')
                                        <x-system.modal id="editDeductionModal-{{ $deduction->id }}"
                                        form="editDeductionForm-{{ $deduction->id }}"
                                        title="Edit Deduction - {{ $deduction->name }}" size="md" :inside="true">
                                        <form action="{{ route('hr.deductions.update', [$employee->id, $deduction->id]) }}"
                                            method="POST" id="editDeductionForm-{{ $deduction->id }}">
                                            @csrf
                                            @method('PUT')

                                            <div class="mb-3">
                                                <label for="name-{{ $deduction->id }}" class="form-label">Deduction
                                                    Name</label>
                                                <input type="text" name="name" id="name-{{ $deduction->id }}"
                                                    class="form-control" value="{{ $deduction->name }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="total_amount-{{ $deduction->id }}" class="form-label">Total
                                                    Amount</label>
                                                <input type="number" step="0.01" name="total_amount"
                                                    id="total_amount-{{ $deduction->id }}" class="form-control"
                                                    value="{{ $deduction->total_amount }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="installments-{{ $deduction->id }}"
                                                    class="form-label">Installments</label>
                                                <input type="number" name="installments"
                                                    id="installments-{{ $deduction->id }}" class="form-control"
                                                    value="{{ $deduction->installments }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="description">
                                                    <span class="text-muted">Description</span>
                                                    <textarea name="description" id="description" class="form-control">{{ $deduction->description }}</textarea>
                                                </label>
                                            </div>

                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary">Update Deduction</button>
                                            </div>
                                        </form>
                                    </x-system.modal>
                                    @endcan
                                @endforeach


                            </div>

                            {{-- Create New Deduction Form --}}
                            @can('create_deductions')
                                <form action="{{ route('hr.deductions.store', $employee->id) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="name" class="form-label">Deduction Name</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="total_amount" class="form-label">Amount</label>
                                        <input type="number" step="0.01" name="total_amount" id="total_amount"
                                            class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="installments" class="form-label">Installments</label>
                                        <input type="number" name="installments" id="installments" class="form-control"
                                            required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="description">
                                            <span class="text-muted">Description</span>
                                            <textarea name="description" id="description" class="form-control"></textarea>
                                        </label>
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary">Add Deduction</button>
                                    </div>
                                </div>
                            </form>
                            @endcan
                            <hr>

                        </x-system.modal>
                    @endcanany

                 @can('view_employees')
                     <a href="{{ route($prefix . '.index') }}" class="btn btn-outline-secondary btn-custom">BACK TO
                            LIST</a>
                 @endcan
                    @canany(['view_deductions', 'create_deductions', 'edit_deductions'])
                        <x-system.modal-button class="btn btn-danger btn-custom me-2" data-bs-toggle="modal"
                            id="ManageDeductions" text="Manage Deductions" />
                    @endcanany
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

            <div class="mb-4">
                <h4 class="section-title">Other Information</h4>
                <div class="info-grid">
                    <div><strong>National ID:</strong> {{ $employee->national_id }}</div>
                    <div><strong>TIN Number:</strong> {{ $employee->tin_number }}</div>
                </div>
            </div>hm

            {{-- Attahmhments section --}}
            @can('view_attachments')
                <div class="mb-4">
                    <h4 class="section-title">Employment Attachments</h4>
                    <div class="info-grid">
                        <div>
                            <strong>National id:</strong>
                            @if ($attachments->where('type', 'national_id')->first())
                                <x-system.attachment-file-icon :path="$attachments->where('type', 'national_id')->first()?->path" type="pdf" :attachmentName="$attachments->where('type', 'national_id')->first()?->filename" />
                            @else
                                <h1>---------------</h1>
                            @endif
                        </div>
                        <div>
                            <strong>Local Government Letter:</strong>
                            @if ($attachments->where('type', 'letter')->first())
                                <x-system.attachment-file-icon :path="$attachments->where('type', 'letter')->first()?->path" type="pdf" :attachmentName="$attachments->where('type', 'letter')->first()?->filename" />
                            @else
                                <h1>---------------</h1>
                            @endif
                        </div>
                        <div>
                            <strong>Passport:</strong>
                            @if ($attachments->where('type', 'passport_photo')->first())
                                <x-system.attachment-file-icon :path="$attachments->where('type', 'passport_photo')->first()?->path" type="pdf" :attachmentName="$attachments->where('type', 'passport_photo')->first()?->filename" />
                            @else
                                <h1>---------------</h1>
                            @endif
                        </div>
                        <div>
                            <strong>TIN:</strong>
                            @if ($attachments->where('type', 'tin')->first())
                                <x-system.attachment-file-icon :path="$attachments->where('type', 'tin')->first()?->path" type="pdf" :attachmentName="$attachments->where('type', 'tin')->first()?->filename" />
                            @else
                                <h1>---------------</h1>
                            @endif
                        </div>
                        <div>
                            <strong>TIN:</strong>
                            @if ($attachments->where('type', 'cv')->first())
                                <x-system.attachment-file-icon :path="$attachments->where('type', 'cv')->first()?->path" type="pdf" :attachmentName="$attachments->where('type', 'cv')->first()?->filename" />
                            @else
                                <h1>---------------</h1>
                            @endif
                        </div>
                        <div>
                            <strong>Certificates:</strong> <br>
                            @php
                                $certificates = $attachments->where('type', 'certificate');
                                $counter = 1;
                            @endphp
                            @if ($certificates)
                                @foreach ($certificates as $attachment)
                                    {{-- Here goes the new model --}}
                                    <p>{{ $counter++ }}:</p>
                                    <x-system.attachment-file-icon :path="$attachment->path" type="pdf" :attachmentName="$attachment->filename" />
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

            @endcan
                        {{-- Attachments section ends here --}}
            @canany(['edit_employees', 'edit_own_employees'])
             <div casyass="text-end mt-4">
                    <a href="{{ route($prefix . '.edit', $employee->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                </div>   
            @endcanany
        </div>
    </div>
</div>
    @canany(['view_own_payrolls', 'view_payroll'], $post)
        <div class="col-12 mt-5">
        <x-system.displays.employee-payrolls :employee="$employee" />
    </div>
    @endcanany
