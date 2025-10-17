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
])
@php
    $pay_grades = App\Models\PayGrade::all();
    $statuses = App\Models\Status::all();
    $currentStatusId = $employee->currentStatus?->status_id;
    $currentReason = $employee->currentStatus?->reason;
    $currentEffectiveDate = $employee->currentStatus?->effective_date;
@endphp

<div class="mt-4">
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-white">
                <i class="bi bi-person-circle me-2"></i>
                Employee Profile - {{ $employee->full_name }}
            </h4>
        </div>
        <div class="card-body">

            <div class="d-flex align-items-center mb-4">
                <div class="row justify-content-start">
                    <div class="col-sm-12 col-md-6">
                        {{-- Back to List --}}
                        @canany(['view_employees'])
                            <div class="col-md-6 mb-4 mt-2">
                                <a href="{{ route($prefix . '.index') }}"
                                    class="btn btn-block btn-outline-secondary btn-custom">BACK
                                    TO
                                    LIST</a>
                            </div>
                        @endcanany
                    </div>
                    <div class="d-inline-flex align-items-center col-sm-12 col-md-12">
                        <img src="{{ $employee->profile_picture
                            ? asset('storage/' . $employee->profile_picture)
                            : 'https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg' }}"
                            alt="Profile Image" class="profile-img me-3">
                        <div>
                            <h2 class="mb-0">{{ $employee->full_name }}
                                <span
                                    class="custom-badge {{ $employee->currentStatus?->status->name == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $employee->currentStatus?->status->name }}
                                </span>
                            </h2>
                            <span class="lead">Registered AS: <b>
                                    {{ $employee->user->roles->where('name', '!=', 'EMPLOYEE')->first()->name ?? 'No Role' }}
                                </b></span>
                            <p class="text-muted"><span>{{ $employee->employee_type }}</span><span> | </span>
                                <span>
                                    {{ $employee->pay_grades->where('pivot.status', true)->first()?->name ?? 'No Active Paygrade' }}
                                </span>
                            </p>
                        </div>
                    </div>


                    <div class="row justify-content-start">

                        {{-- Profile Image Update (ADMIN, HR_OFFICER) --}}
                        @can('edit_own_employees')
                            @hasrole('EMPLOYEE')
                                <div class="col-md-6 mt-2">
                                    <x-system.modal-button class="btn btn-block btn-primary btn-custom" data-bs-toggle="modal"
                                        id="UpdateProfilePhoto" text="UPDATE PROFILE IMAGE" />
                                </div>
                            @endhasanyrole
                        @endcan
                        {{-- PayGrade Update (PAYROLL_MANAGER) --}}
                        @can('edit_paygrade')
                            @hasrole('EMPLOYEE')
                                <div class="col-md-6 mt-2">
                                    <x-system.modal-button class="btn btn-block btn-primary btn-custom" data-bs-toggle="modal"
                                        id="UpdatePayGrade" text="UPDATE PAYGRADE" />
                                </div>
                            @endhasrole
                        @endcan
                        {{-- Employee Status update --}}
                        @can('edit_employee_status')
                            @hasrole('EMPLOYEE')
                                <div class="col-md-6 mt-2">
                                    <x-system.modal-button class="btn btn-block btn-primary btn-custom me-2"
                                        data-bs-toggle="modal" id="UpdateEmployeeStatus" text="UPDATE EMPLOYEE STATUS" />
                                </div>
                            @endhasrole
                        @endcan

                        {{-- Manage Allowances Button --}}
                        @canany(['edit_allowances', 'view_allowances', 'create_allowances'])
                            @hasrole('EMPLOYEE')
                                <div class="col-md-6 mt-2">
                                    <x-system.btn-view class="btn btn-block btn-primary btn-custom me-2" :route="route('employee.manage.employee.allowances.index', $employee)"
                                        text="Manage Allowances" />
                                </div>
                            @endhasrole
                        @endcanany
                        {{-- Manage Loans Button --}}
                        {{-- Manage Deductions Button --}}
                        @canany(['edit_deductions', 'view_deductions', 'create_deductions'])
                            @hasrole('EMPLOYEE')
                                <div class="col-md-6 mt-2">
                                    <x-system.btn-view class="btn btn-block btn-primary btn-custom me-2" :route="route('employee.manage.deductions.index', $employee)"
                                        text="Manage Deductions" />
                                </div>
                            @endhasrole
                        @endcanany
                    </div>
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

            @php
                $activePayGrade = $employee->pay_grades->firstWhere('pivot.status', true);
                $salary = $activePayGrade
                    ? ($activePayGrade->pivot->base_salary_override > 0
                        ? $activePayGrade->pivot->base_salary_override
                        : $activePayGrade->base_salary)
                    : null;
            @endphp
            <div><strong>Salary:</strong> {{ $salary ? number_format($salary, 2) . ' Tshs' : 'N/A' }}</div>

            <div class="mb-4 mt-5">
                <h4 class="section-title">Other Information</h4>
                <div class="info-grid">
                    <div><strong>National ID:</strong> {{ $employee->national_id }}</div>
                    <div><strong>TIN Number:</strong> {{ $employee->tin_number }}</div>
                </div>
            </div>
            @hasanyrole(['ADMIN', 'HR_OFFICER', 'EMPLOYEE'])
                {{-- Attachments Section --}}
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
            @endhasanyrole

            {{-- Edit Button --}}
            @canany(['edit_employees', 'edit_own_employees'])
                <div class="text-end mt-4">
                    <a href="{{ route($prefix . '.edit', $employee->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                </div>
            @endcanany
        </div>
    </div>
</div>





<x-system.modal id="UpdateProfilePhoto" form="updateProfilePhotoForm" title="New Profile photo">
    <form action="{{ route($prefix . '.updateProfilePhoto', $employee->id) }}" id="updateProfilePhotoForm"
        enctype="multipart/form-data" method="POST">
        @csrf
        <div class="form-group">
            <div class="col-md-12 mb-4">
                <x-system.form-inputs.file-upload name="profile_picture" label="Profile Picture"
                    accept="image/jpeg,image/png,image/jpg" maxSize="2" icon="mdi-camera" col="12" required />
            </div>
        </div>
    </form>
</x-system.modal>




<x-system.modal id="UpdatePayGrade" form="UpdatePayGradeForm" title="Update PayGrade">
    <form action="{{ route('employee.manage.employees.UpdatePayGrade', $employee) }}" id="UpdatePayGradeForm"
        enctype="multipart/form-data" method="POST">

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
                    <input data-format="number" type="text" name="base_salary_override" class="form-control"
                        placeholder="e.g., 1200000"
                        value="{{ old('base_salary_override', optional($employee->pay_grades->firstWhere('pivot.status', true))->pivot->base_salary_override ?? '') }}">
                </div>
                @error('base_salary_override')
                    <span class="text-danger d-block">{{ $message ?? 'an error occured' }}</span>
                @enderror
            </div>
        </div>
    </form>
</x-system.modal>





<x-system.modal id="UpdateEmployeeStatus" form="UpdateEmployeeStatusForm" title="Update Employee Status">
    <form action="{{ route('employee.manage.employees.updateStatus', $employee) }}" id="UpdateEmployeeStatusForm"
        method="POST">
        @csrf

        <div class="form-group row">
            <div class="col-md-6 mb-4">
                <label for="status" class="text-dark font-weight-medium">Status</label>
                <select name="status" id="status" class="form-control" required>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}"
                            {{ old('status', $currentStatusId) == $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-4">
                <label for="effective_date" class="text-dark font-weight-medium">Effective
                    Date</label>
                <input type="date" name="effective_date" id="effective_date" class="form-control"
                    value="{{ old('effective_date', optional($currentEffectiveDate)->format('Y-m-d')) }}">
            </div>

            <div class="col-md-12 mb-4">
                <label for="reason" class="text-dark font-weight-medium">Reason</label>
                <textarea name="reason" id="reason" rows="3" class="form-control">{{ old('reason', $currentReason) }}</textarea>
            </div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Update Status</button>
        </div>
    </form>
</x-system.modal>


<div class="col-12 mt-5">
    <x-system.displays.employee-payrolls :employee="$employee" />
</div>
