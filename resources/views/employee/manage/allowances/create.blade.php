@extends('layouts.system')
@section('content')
    @can('create_allowances')
        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-border-bottom">
                    <h2>Add Allowance</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('employee.manage.allowances.store') }}" method="POST" >
                        @csrf
                        <div class="row">

                            {{-- Allowance Name --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">Allowance Name</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-receipt"></span>
                                    <input type="text" name="name" class="form-control" placeholder="travel allowance"
                                        value="{{ old('name') }}">
                                </div>
                                @error('name')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">Description</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-file-document"></span>
                                    <input type="text" name="description" class="form-control" placeholder="This is the allowance for travel cases "
                                        value="{{ old('description') }}">
                                </div>
                                @error('description')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Is is Taxed --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-dark font-weight-medium">Taxable</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-cash"></span>
                                    <select name="is_taxable" class="form-control">
                                        <option value="" disabled {{ old('is_taxable') ? '' : 'selected' }}>Taxable
                                        </option>
                                        <option value={{1}} {{ old('is_taxable') == true ? 'selected' : '' }}>Yes</option>
                                        <option value={{0}} {{ old('is_taxable') == false ? 'selected' : '' }}>No
                                        </option>

                                    </select>
                                </div>
                                @error('is_taxable')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="col-md-12 text-end mt-6">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save"></i> Save Allowance
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection
