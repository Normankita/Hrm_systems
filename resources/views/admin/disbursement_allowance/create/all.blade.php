@extends('layouts.system')

@section('content')
    <div class="row justify content-center" id="emps">
        <div class="col-12">
            <!-- Card for displaying three catregories of allawances
                    which are group, individual, category allowances -->
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="col-12">
                            <div>

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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
