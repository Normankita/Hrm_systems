@props(['employee','route', 'internal_route'])

@php
$departments= App\Models\Department::all();
$roles = App\Models\Role::where('name', '!=', 'ADMIN')->get();
$pay_grades = App\Models\PayGrade::all();
@endphp

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

                {{-- Middle Name --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Middle Name</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-account"></span>
                        <input type="text" name="middle_name" class="form-control"
                            value="{{ old('middle_name', $employee->middle_name) }}" required>
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
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Gender</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-gender-male-female"></span>
                        <select name="gender" class="form-control" required>
                            <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>
                                Male</option>
                            <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>
                                Female
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
                        <select name="marital_status" class="form-control" required>
                            <option value="" disabled>Marital
                                Status
                            </option>
                            <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married
                            </option>
                            <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single
                            </option>

                        </select>
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
                        @foreach ($departments as $department)
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
                                                                {{-- Pay Grade --}}
                <div class="col-md-3 mb-4">
                    <label for="pay_grade_id" class="text-dark font-weight-medium">PayGrade</label>
                    <select name="pay_grade_id" id="pay_grade_id" class="form-control">
                        <option value="" disabled {{ old('pay_grade_id') ? '' : 'selected' }}>Select
                            PayGrade</option>
                        @foreach ($pay_grades as $pay_grade)
                            <option value="{{ $pay_grade->id }}" {{ old('pay_grade_id') == $pay_grade->id ? 'selected' : '' }}>
                                {{ $pay_grade->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                                {{-- Effective from date --}}
                <div class="col-md-3 mb-4">
                    <label class="text-dark font-weight-medium">Effective From</label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-calendar"></span>
                        <input type="date" name="effective_from" class="form-control"
                            value="{{ old('effective_from') }}">
                    </div>
                    @error('effective_from')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>
                                {{-- Salary --}}
                <div class="col-md-6 mb-4">
                    <label class="text-dark font-weight-medium">Base Salary Override  <span class="text-muted font-weight-lighter text-sm">(optional)</span> </label>
                    <div class="input-group">
                        <span class="input-group-text mdi mdi-cash-multiple"></span>
                        <input type="number" name="base_salary_override" class="form-control"
                            placeholder="e.g., 1200000" value="{{ old('base_salary_override') }}">>
                    </div>
                    @error('base_salary_override')
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>

                <x-system.forms.update-employee-attachemnets :attachments="$employee->attachments" />

                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-start">
                        {{-- Submit Button on the left --}}
                        <button type="submit" class="btn btn-primary mx-2">
                            <i class="mdi mdi-content-save-edit"></i> Update Employee
                        </button>
                        {{-- Change Password Button on the right --}}
                        <x-system.modal-button class="btn btn-secondary" text="Change Password"
                            id="UpdatePassword" />
                    </div>
                </div>

            </div>
        </form>

        {{-- Modal for Changing Password --}}
        <x-system.modal id="UpdatePassword" form="updatePasswordForm" title="ChangePassword">
            <form id="updatePasswordForm" method="POST" action="{{ route($internal_route, $employee->id) }}">
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
