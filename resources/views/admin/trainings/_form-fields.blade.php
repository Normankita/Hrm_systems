@php
    $selectedDepartmentIds = old(
        'department_ids',
        $training ? $training->departments->pluck('id')->all() : []
    );
    $selectedInstructorIds = old(
        'instructor_ids',
        $training ? $training->instructors->pluck('id')->all() : []
    );
@endphp

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label fw-medium">Training Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required
            value="{{ old('name', $training?->name) }}" placeholder="e.g. Workplace Safety 2026">
        @error('name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">Duration</label>
        <input type="text" name="duration" class="form-control"
            value="{{ old('duration', $training?->duration) }}" placeholder="e.g. 3 days">
        @error('duration')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">Type <span class="text-danger">*</span></label>
        <select name="type" class="form-control" required>
            <option value="">-- Select --</option>
            @foreach (\App\Enums\TrainingTypeEnum::cases() as $type)
                <option value="{{ $type->value }}" @selected(old('type', $training?->type) === $type->value)>
                    {{ $type->value }}
                </option>
            @endforeach
        </select>
        @error('type')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-control" required>
            @foreach (\App\Enums\TrainingStatusEnum::cases() as $status)
                <option value="{{ $status->value }}" @selected(old('status', $training?->status ?? 'Scheduled') === $status->value)>
                    {{ $status->value }}
                </option>
            @endforeach
        </select>
        @error('status')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">Target Departments</label>
        <select name="department_ids[]" class="form-control training-select2-departments" multiple>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}"
                    @selected(in_array($department->id, $selectedDepartmentIds))>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">Leave empty to include all departments</small>
        @error('department_ids')
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror
        @error('department_ids.*')
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium d-flex justify-content-between align-items-center">
            <span>Instructors</span>
            <a href="{{ route('admin.instructors.index') }}" class="small" target="_blank">+ Register instructor</a>
        </label>
        <select name="instructor_ids[]" class="form-control training-select2-instructors" multiple>
            @forelse ($registeredInstructors as $instructor)
                <option value="{{ $instructor->id }}"
                    @selected(in_array($instructor->id, $selectedInstructorIds))>
                    {{ $instructor->displayLabel() }}
                </option>
            @empty
                <option value="" disabled>No instructors registered yet</option>
            @endforelse
        </select>
        <small class="text-muted">Select from registered instructors</small>
        @error('instructor_ids')
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror
        @error('instructor_ids.*')
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">Start Date <span class="text-danger">*</span></label>
        <input type="date" name="start_date" class="form-control" required
            value="{{ old('start_date', $training?->start_date?->format('Y-m-d')) }}">
        @error('start_date')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">End Date</label>
        <input type="date" name="end_date" class="form-control"
            value="{{ old('end_date', $training?->end_date?->format('Y-m-d')) }}">
        @error('end_date')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">Location</label>
        <input type="text" name="location" class="form-control"
            value="{{ old('location', $training?->location) }}" placeholder="Venue or link">
        @error('location')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label fw-medium">Description</label>
        <textarea name="description" class="form-control" rows="3"
            placeholder="Training objectives and notes">{{ old('description', $training?->description) }}</textarea>
        @error('description')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>
