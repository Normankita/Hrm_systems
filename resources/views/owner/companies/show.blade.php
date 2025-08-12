@extends('layouts.system')


@section('content')
    <div class="container my-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0 text-white">Company Details</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @if (!$company->admin)
                        <div class="col-md-12 mb-4">
                            <x-system.modal-button class="btn btn-primary btm-sm" id="addAdmin" text="Add Admin" />
                            <x-system.modal size="modal-lg" id="addAdmin">
                                <form action="{{ route('owner.companies.addAdmin', $company->id) }}" method="POST">
                                    @csrf
                                    <div class="row justify-content-center">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="hidden" name="company_id" value="{{ $company->id }}">
                                                <label for="name" class="form-label">name</label>
                                                <input type="text" name="name" class="form-control" id="name"
                                                    placeholder="Name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control" id="email"
                                                    placeholder="Email" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="gender">Gender</label>
                                            <select class="form-control" name="gender" id="gender" required>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="date_of_birth"></label>
                                            <input type="date"
                                                value="{{ Carbon\Carbon::parse(old('date_of_birth'))->format('Y-m-d') }}"
                                                name="date_of_birth" class="form-control" required>
                                            @error('date_of_birth')
                                                <span>{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 form-group">
                                            <label for="password">Password</label>
                                            <input type="password" value="" name="password" class="form-control"
                                                required>
                                            @error('password')
                                                <span>{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 form-group">
                                            <label for="password_confirmation">
                                                Confirm Password
                                            </label>
                                            <input type="password" name="password_confirmation" class="form-control"
                                                required>
                                            @error('password_confirmation')
                                                <span>{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Admin</button>
                                </form>
                            </x-system.modal>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Name:</strong>
                            <p>{{ $company->name }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Address:</strong>
                            <p>{{ $company->address }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Contact Number:</strong>
                            <p>{{ $company->contact_number }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Email:</strong>
                            <p>{{ $company->email }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Brela Registration Number:</strong>
                            <p>{{ $company->brela_reg_number }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>TIN Number:</strong>
                            <p>{{ $company->tin_number }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong>
                            <p>
                                <span class="badge {{ $company->isActive ? 'bg-success' : 'bg-danger' }}">
                                    {{ $company->isActive ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                            <div class="">
                                <form action="{{ route('owner.companies.toggleStatus', $company->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-sm {{ $company->isActive ? 'btn-danger' : 'btn-success' }}">
                                        {{ $company->isActive ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h5>Admin Details</h5>
                        @if (!$company->admin)
                            <p>No admin found for this company.</p>
                        @else
                            <ul class="list-group">
                                @php
                                    $admin = $company->admin;
                                @endphp
                                <div class="mb-3">
                                    <strong>Admin:</strong>
                                    <p>
                                    <p>
                                        {{ $admin->name }}
                                    </p>
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <strong>Email:</strong>
                                    <p>
                                    <p>
                                        {{ $admin->email }}
                                    </p>
                                    </p>
                                </div>
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="row mt-5">
                    <h3>COMPANY EMPLOYEES</h3>
                    <div class="col-md-12">
                        <x-system.table class="dt-table">
                            <x-slot name="head">
                                <x-system.table-head>
                                    <tr>
                                        <th>Name</th>
                                        <th>Role</th>
                                    </tr>
                                </x-system.table-head>
                            </x-slot>

                            <x-slot name="body">
                                <x-system.table-body>
                                    @foreach ($company->employees as $employee)
                                        <tr>
                                            <td>{{ $employee->full_name }}</td>
                                            <td>
                                                {{ $employee->user?->activeRole()->name ?? "N/A"}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </x-system.table-body>
                            </x-slot>
                        </x-system.table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
