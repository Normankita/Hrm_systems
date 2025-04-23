@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-body p-30">
                    <form action="{{ route('admin.employees.store') }}" method="POST">
                        @csrf
                        <div class="row">

                            {{-- Full Name --}}
                            {{-- First Name --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">First Name</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-account"></span>
                                    <input type="text" name="first_name" class="form-control" placeholder="John"
                                        required>
                                    @error('first_name')
                                        <span>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            {{-- Last Name --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">Last Name</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-account"></span>
                                    <input type="text" name="last_name" class="form-control" placeholder="Doe" required>
                                    @error('last_name')
                                        <span>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            {{-- Middle Name --}}

                            {{-- Gender --}}
                            <div class="col-md-12 mb-4">
                                <label class="text-dark font-weight-medium">Gender</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-gender-male-female"></span>
                                    <select name="gender" class="form-control" required>
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Date of Birth --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">Date of Birth</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-calendar"></span>
                                    <input type="date" name="date_of_birth" class="form-control" required>
                                    @error('date_of_birth')
                                        <span>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-email"></span>
                                    <input type="email" name="email" class="form-control" placeholder="john@example.com"
                                        required>
                                    @error('email')
                                        <span>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Phone Number --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-phone"></span>
                                    <input type="text" name="phone_number" class="form-control"
                                        placeholder="+255712345678" required>
                                    @error('phone_number')
                                        <span>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- National ID --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">National ID</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-card-account-details"></span>
                                    <input type="text" name="national_id" class="form-control"
                                        placeholder="1234567890123456" required>
                                    @error('national_id')
                                        <span>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- TIN Number --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">TIN Number</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-currency-usd"></span>
                                    <input type="text" name="tin_number" class="form-control" placeholder="Optional">
                                    @error('tin_number')
                                        <span>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Marital Status --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">Marital Status</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-heart"></span>
                                    <input type="text" name="marital_status" class="form-control"
                                        placeholder="e.g., Single, Married">
                                    @error('marital_status')
                                        <span>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Residential Address --}}
                            <div class="col-md-12 mb-4">
                                <label class="text-dark font-weight-medium">Residential Address</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-home-map-marker"></span>
                                    <input type="text" name="residential_address" class="form-control"
                                        placeholder="e.g., Sinza Mori, Dar es Salaam">
                                        
                                </div>
                            </div>

                            {{-- Employee Type --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">Employee Type</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-account-box-outline"></span>
                                    <select name="employee_type" class="form-control" required>
                                        <option value="" disabled selected>Select Type</option>
                                        <option value="Permanent">Permanent</option>
                                        <option value="Contract">Contract</option>
                                        <option value="Probation">Probation</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Date of Hire --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">Date of Hire</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-calendar-check"></span>
                                    <input type="date" name="date_of_hire" class="form-control" required>
                                </div>
                            </div>

                            {{-- Department --}}
                            <div class="col-md-6 mb-4">
                                <label for="department_id" class="text-dark font-weight-medium">Department</label>
                                <select name="department_id" id="department_id" class="form-control" required>
                                    <option value="" disabled selected>-- Select Department --</option>
                                    @foreach (App\Models\Department::all() as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Role --}}
                            <div class="col-md-6 mb-4">
                                <label for="role_id" class="text-dark font-weight-medium">Designation (Role)</label>
                                <select name="role_id" id="role_id" class="form-control" required>
                                    <option value="" disabled selected>Select designation</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Submit Button --}}
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save"></i> Save Employee
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
