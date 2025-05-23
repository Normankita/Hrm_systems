@extends('layouts.system')
@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0 text-white">Edit Company</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.companies.update', $company->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <h3>General Setups</h3>
                        <div class="col-sm-12 col-md-12">
                            <div class="mb-3">
                                <label for="name" class="form-label">Company Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $company->name }}" required>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ $company->email }}" required>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-3">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number"
                                    value="{{ $company->contact_number }}" required>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="{{ $company->address }}" required>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-3">
                                <label for="brela_reg_number" class="form-label">BRELA Registration Number</label>
                                <input type="text" class="form-control" id="brela_reg_number" name="brela_reg_number"
                                    value="{{ $company->brela_reg_number }}" required>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="mb-3">
                                <label for="tin_number" class="form-label">Company TIN Number</label>
                                <input type="text" class="form-control" id="tin_number" name="tin_number"
                                    value="{{ $company->tin_number }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5 mb-3">
                        <div class="col-sm-12 col-md-6">
                            <h3>Configurations Setups</h3>
                            @foreach (App\Models\Setting::all() as $setting)
                                <div class="mb-3">
                                    <label for="{{ $setting->name }}" class="form-label">{{ $setting->name }}</label>
                                    <input type="day" class="form-control" id="{{ $setting->name }}"
                                        name="{{ $setting->name }}" value="{{ $setting->value }}" required>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Company</button>
                </form>
            </div>
        </div>
    </div>
@endsection
