@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-body p-30">
                    <form action="{{ route('employees.profile.update', $employee->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">

                            {{-- First Name --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">First Name</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-account"></span>
                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $employee->first_name) }}" required>
                                </div>
                            </div>

                            {{-- Last Name --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">Last Name</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-account"></span>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $employee->last_name) }}" required>
                                </div>
                            </div>

                            {{-- Gender --}}
                            <div class="col-md-12 mb-4">
                                <label class="text-dark font-weight-medium">Gender</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-gender-male-female"></span>
                                    <select name="gender" class="form-control" required>
                                        <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender', $employee->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Date of Birth --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">Date of Birth</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-calendar"></span>
                                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $employee->date_of_birth) }}" required>
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-email"></span>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}" required>
                                </div>
                            </div>

                            {{-- Phone Number --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-phone"></span>
                                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $employee->phone_number) }}" required>
                                </div>
                            </div>

                            {{-- National ID --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">National ID</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-card-account-details"></span>
                                    <input type="text" name="national_id" class="form-control" value="{{ old('national_id', $employee->national_id) }}" required>
                                </div>
                            </div>

                            {{-- TIN Number --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">TIN Number</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-currency-usd"></span>
                                    <input type="text" name="tin_number" class="form-control" value="{{ old('tin_number', $employee->tin_number) }}">
                                </div>
                            </div>

                            {{-- Marital Status --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">Marital Status</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-heart"></span>
                                    <input type="text" name="marital_status" class="form-control" value="{{ old('marital_status', $employee->marital_status) }}">
                                </div>
                            </div>

                            {{-- Residential Address --}}
                            <div class="col-md-12 mb-4">
                                <label class="text-dark font-weight-medium">Residential Address</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-home-map-marker"></span>
                                    <input type="text" name="residential_address" class="form-control" value="{{ old('residential_address', $employee->residential_address) }}">
                                </div>
                            </div>
                 
                            {{-- Submit Button --}}
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save-edit"></i> Update Employee
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
