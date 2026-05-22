@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-body p-30">
                    <form action="{{ route('employees.profile.update_password', $employee->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">

                            {{-- Current Password --}}
                            <div class="col-md-12 mb-4">
                                <label class="text-dark font-weight-medium">Current Password</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-lock"></span>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>
                                @error('current_password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- New Password --}}
                            <div class="col-md-12 mb-4">
                                <label class="text-dark font-weight-medium">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-lock-plus"></span>
                                    <input type="password" name="new_password" class="form-control" required minlength="8">
                                </div>
                                @error('new_password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Confirm New Password --}}
                            <div class="col-md-12 mb-4">
                                <label class="text-dark font-weight-medium">Confirm New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text mdi mdi-lock-check"></span>
                                    <input type="password" name="new_password_confirmation" class="form-control" required minlength="8">
                                </div>
                                @error('new_password_confirmation')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save-edit"></i> Update Password
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
