@props(['prefix'])

@php
    $departments = App\Models\Department::all();
    $roles = App\Models\Role::whereNotIn('name', ['ADMIN', 'EMPLOYEE'])->get();
    $pay_grades = App\Models\PayGrade::all();
@endphp

<div class="card">
    <div class="card-body p-30">
        <div>
            <form id="uploadFile" action="{{ route($prefix . 'excel.import') }}" method="POST"
                enctype="multipart/form-data">
                <div>
                    @csrf
                    <div class="form-group">
                        <label for="file">Import Employees from Excel</label>
                        <input type="file" name="file" id="file" class="form-control">
                        @error('file')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </form>
            <div class="form-group">
                <button form="uploadFile" type="submit" class="btn btn-primary">Import</button>
                <a download="download" href="{{ asset('shared/employee.csv') }}" class="text-white">
                    <button download type="submit" class="btn btn-secondary">
                        <i class="mdi mdi-file-excel"></i> Download Sample Excel
                    </button>
                </a>
            </div>
        </div>

        <div class="mt-4 mb-4">
            <h3 class="text-dark font-weight-bold">Create New Employee</h3>
            <p class="text-muted">Fill in the details below to create a new employee record.</p>

        </div>
        <form action="{{ route($prefix . 'store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">

                {{-- First Name --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">First Name</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-account"></span>
                        <input type="text" name="first_name" class="form-control" placeholder="John"
                            value="{{ old('first_name') }}">
                    </div>
                    @error('first_name')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Middle Name --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Middle Name</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-account"></span>
                        <input type="text" name="middle_name" class="form-control" placeholder="Smith"
                            value="{{ old('middle_name') }}">
                    </div>
                    @error('middle_name')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>
                {{-- Last Name --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Last Name</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-account"></span>
                        <input type="text" name="last_name" class="form-control" placeholder="Doe"
                            value="{{ old('last_name') }}">
                    </div>
                    @error('last_name')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Gender --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Gender</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-gender-male-female"></span>
                        <select name="gender" class="form-control">
                            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender
                            </option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female
                            </option>

                        </select>
                    </div>
                    @error('gender')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Date of Birth --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Date of Birth</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-calendar"></span>
                        <input type="date" name="date_of_birth" class="form-control"
                            value="{{ old('date_of_birth') }}">
                    </div>
                    @error('date_of_birth')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Email</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-email"></span>
                        <input type="email" name="email" class="form-control" placeholder="john@example.com"
                            value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Phone Number --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-phone"></span>
                        <input type="text" name="phone_number" class="form-control" placeholder="+255712345678"
                            value="{{ old('phone_number') }}">
                    </div>
                    @error('phone_number')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- National ID --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">National ID</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-card-account-details"></span>
                        <input type="text" name="national_id" class="form-control" placeholder=""
                            value="{{ old('national_id') }}" required>
                    </div>
                    @error('national_id')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- TIN Number --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">TIN Number</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-currency-usd"></span>
                        <input type="text" name="tin_number" class="form-control" placeholder="Optional"
                            value="{{ old('tin_number') }}">
                    </div>
                    @error('tin_number')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Marital Status --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Marital Status</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-heart"></span>
                        <select name="marital_status" class="form-control">
                            <option value="" disabled {{ old('marital_status') ? '' : 'selected' }}>Marital
                                Status
                            </option>
                            <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married
                            </option>
                            <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single
                            </option>
                        </select>
                    </div>

                    @error('marital_status')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Residential Address --}}
                <div class="col-md-12 mb-4">
                    <label class="text-dark font-weight-medium">Residential Address <span
                            class="text-muted font-weight-lighter text-sm">(optional)</span> </label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-home-map-marker"></span>
                        <input type="text" name="residential_address" class="form-control"
                            placeholder="e.g., Sinza Mori, Dar es Salaam" value="{{ old('residential_address') }}">
                    </div>
                    @error('residential_address')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Employee Type --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Employee Type</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-account-box-outline"></span>
                        <select name="employee_type" class="form-control">
                            <option value="" disabled {{ old('employee_type') ? '' : 'selected' }}>
                                Select Type</option>
                            <option value="Permanent" {{ old('employee_type') == 'Permanent' ? 'selected' : '' }}>
                                Permanent</option>
                            <option value="Contract" {{ old('employee_type') == 'Contract' ? 'selected' : '' }}>
                                Contract</option>
                            <option value="Probation" {{ old('employee_type') == 'Probation' ? 'selected' : '' }}>
                                Probation</option>
                        </select>
                    </div>
                    @error('employee_type')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Date of Hire --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Date of Hire</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-calendar-check"></span>
                        <input type="date" name="date_of_hire" class="form-control"
                            value="{{ old('date_of_hire') }}">
                    </div>
                    @error('date_of_hire')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Department --}}
                <div class="col-md-6 mb-4">
                    <label for="department_id" class="text-dark font-weight-medium">Department</label>
                    <select name="department_id" id="department_id" class="form-control">
                        <option value="" disabled {{ old('department_id') ? '' : 'selected' }}>-- Select
                            Department --</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Role --}}
                <div class="col-md-6 mb-4">
                    <label for="role_id" class="text-dark font-weight-medium">Designation (Role)</label>
                    <select name="role_id" id="role_id" class="form-control">
                        <option value="" disabled {{ old('role_id') ? '' : 'selected' }}>Select
                            designation</option>
                        @foreach ($roles as $role)
                            @php
                                if ($role->name == 'OWNER' || $role->name == 'ADMIN') {
                                    continue;
                                }
                            @endphp
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>
                {{-- Pay Grade --}}
                <div class="col-md-6 mb-4">
                    <label for="pay_grade_id" class="text-dark font-weight-medium">PayGrade</label>
                    <select name="pay_grade_id" id="pay_grade_id" class="form-control">
                        <option value="" disabled {{ old('pay_grade_id') ? '' : 'selected' }}>Select
                            PayGrade</option>
                        @foreach ($pay_grades as $pay_grade)
                            <option value="{{ $pay_grade->id }}"
                                {{ old('pay_grade_id') == $pay_grade->id ? 'selected' : '' }}>
                                {{ $pay_grade->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('pay_grade_id')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>
                {{-- Salary --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Base Salary Override <span
                            class="text-muted font-weight-lighter text-sm">(optional)</span> </label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-cash-multiple"></span>
                        <input data-format="number" type="text" name="base_salary_override" class="form-control"
                            placeholder="e.g., 1200000" value="{{ old('base_salary_override') }}">
                    </div>
                    @error('base_salary_override')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>

                <x-system.forms.update-employee-attachemnets />

                {{-- Submit Button --}}
                <div class="col-md-12 text-end mt-6">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Save Employee
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
