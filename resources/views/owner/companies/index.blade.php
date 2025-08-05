@extends('layouts.system')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <x-system.modal-button class="btn btn-sm mb-2 btn-primary" id="CreateCompany" text="New Company" />
                            <x-system.modal size="modal-lg" id="CreateCompany">
                                <form action="{{ route('owner.companies.store') }}" method="POST">
                                    @csrf
                                    <div class="row justify-content-center">
                                        <div class="col-md-10">
                                            <div class="row justify-content-center">
                                                <div class="form-group col-md-6">
                                                    <label for="name">Company Name</label>
                                                    <input value="{{ old('name') }}" type="text" class="form-control" id="name"
                                                        aria-describedby="emailHelp" name="name"
                                                        placeholder="Enter Name">
                                                    <div>
                                                        @error('name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="address">Address</label>
                                                    <input value="{{ old('address') }}" type="text" class="form-control" id="address"
                                                        aria-describedby="emailHelp" name="address"
                                                        placeholder="Enter Address">
                                                    <div>
                                                        @error('address')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="">Contact Number</label>
                                                    <input value="{{ old('contact_number') }}" type="text" class="form-control" name="contact_number">
                                                    @error('contact_number')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="email">Email</label>
                                                    <input value="{{ old('email') }}" type="email" name="email" id="email"
                                                        class="form-control">
                                                    @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="brela_reg_number">Brela Registration Number</label>
                                                    <input value="{{ old('brela_reg_number') }}" type="text" class="form-control" id="brela_reg_number"
                                                        name="brela_reg_number" />
                                                    @error('brala_reg_number')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="tin_number">TIN Number</label>
                                                    <input value="{{ old('tin_number') }}" type="text" class="form-control" id="tin_number"
                                                        name="tin_number">
                                                    @error('tin_number')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mt-2">
                                                    <button type="submit" class="btn btn-primary btn-sm btn-pill">Save
                                                        Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </x-system.modal>

                        </div>
                        <div class="col-md-12">
                            <x-system.table class="dt-table">
                                <x-slot name="head">
                                    <x-system.table-head>

                                        <head>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Address</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </head>
                                    </x-system.table-head>
                                </x-slot>
                                <x-slot name="body">
                                    <x-system.table-body>
                                        <tbody>
                                            @foreach ($companies as $company)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $company->name }}</td>
                                                    <td>{{ $company->address }}</td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $company->status ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $company->isActive ? 'active' : 'inactive' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <x-system.btn-view :route="route('owner.companies.show', $company->id)" />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </x-system.table-body>
                                </x-slot>
                            </x-system.table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
