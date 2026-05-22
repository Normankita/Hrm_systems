@extends('layouts.system')

@section('content')
@can('edit_leave')
    <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="container py-5">
                    <h2 class="mb-4">Edit Leave Request</h2>
                
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="list-unstyled">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                
                    <form action="{{ route('employees.leave.update', $leave->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="leave_type_id" class="form-label">Leave Type</label>
                            <select name="leave_type_id" id="leave_type_id" class="form-control">
                                <option value="">Select Leave Type</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('leave_type_id', $leave->leave_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('leave_type_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($leave->start_date)->toDateString()) }}" class="form-control">

                            @error('start_date')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($leave->end_date)->toDateString()) }}" class="form-control">

                            @error('end_date')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason</label>
                            <textarea name="reason" id="reason" rows="4" class="form-control">{{ old('reason', $leave->reason) }}</textarea>
                            @error('reason')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Update Leave Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection
