@extends('layouts.system')

@section('content')

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
                                    value="{{ $company->name ?? old('name') }}" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ $company->email ?? old('email') }}" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-3">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number"
                                    value="{{ $company->contact_number ?? old('contact_number') }}" required>
                                @error('contact_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="{{ $company->address ?? old('address') }}" required>
                                @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-3">
                                <label for="brela_reg_number" class="form-label">BRELA Registration Number</label>
                                <input type="text" class="form-control" id="brela_reg_number" name="brela_reg_number"
                                    value="{{ $company->brela_reg_number ?? old('brela_reg_number') }}" required>
                                @error('brela_reg_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="mb-3">
                                <label for="tin_number" class="form-label">Company TIN Number</label>
                                <input type="text" class="form-control" id="tin_number" name="tin_number"
                                    value="{{ $company->tin_number ?? old('tin_number') }}" required>
                                @error('tin_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5 mb-5">
                        <div class="row col-sm-12 col-md-12">
                            <h3 class="mb-3">Statutory Deductions(%)</h3>
                            @foreach ($contributions as $contribution)
                            @if($contribution->name == "NSSF" || $contribution->name == "PSSSF")
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-3">
                                        <label for="percent_{{ $contribution->id }}"
                                            class="form-label">{{ $contribution->name }} (%)</label>
                                        <input type="number" step="0.01" class="form-control" id="percent_{{ $contribution->id }}"
                                            name="contributions[{{ $contribution->id }}][percent]"
                                            value="{{ $contribution->percent ?? old('contributions.' . $contribution->id . '.percent') }}" required>
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <label for="description_{{ $contribution->id }}"
                                            class="form-label">Description</label>
                                        <input type="text" class="form-control" id="description_{{ $contribution->id }}"
                                            name="contributions[{{ $contribution->id }}][description]"
                                            value="{{ $contribution->description ?? old('contributions.' . $contribution->id . '.description') }}" required>
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <label for="employee_percent_{{ $contribution->id }}"
                                            class="form-label">{{ $contribution->name }} Employee (%)</label>
                                        <input type="number" step="0.01" class="form-control" id="employee_percent_{{ $contribution->id }}"
                                            name="contributions[{{ $contribution->id }}][employee_percent]"
                                            value="{{ $contribution->employee_percent ?? old('contributions.' . $contribution->id . '.employee_percent') }}" required>
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <label for="company_percent_{{ $contribution->id }}"
                                            class="form-label">{{ $contribution->name }} Company (%)</label>
                                        <input type="number" step="0.01" class="form-control" id="company_percent_{{ $contribution->id }}"
                                            name="contributions[{{ $contribution->id }}][company_percent]"
                                            value="{{ $contribution->company_percent ?? old('contributions.' . $contribution->id . '.company_percent') }}" required>
                                    </div>
                                </div>
                            @else
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <label for="percent_{{ $contribution->id }}"
                                            class="form-label">{{ $contribution->name }} (%)</label>
                                        <input type="number" step="0.01" class="form-control" id="percent_{{ $contribution->id }}"
                                            name="contributions[{{ $contribution->id }}][percent]"
                                            value="{{ $contribution->percent ?? old('contributions.' . $contribution->id . '.percent') }}" required>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <label for="description_{{ $contribution->id }}"
                                            class="form-label">Description</label>
                                        <input type="text" class="form-control" id="description_{{ $contribution->id }}"
                                            name="contributions[{{ $contribution->id }}][description]"
                                            value="{{ $contribution->description ?? old('contributions.' . $contribution->id . '.description') }}" required>
                                    </div>
                                </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Company</button>
                </form>
            </div>
        </div>

@endsection
