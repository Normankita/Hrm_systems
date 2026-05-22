<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required
            value="{{ old('name', $instructor?->name) }}" placeholder="Instructor full name">
        @error('name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">Specialization</label>
        <input type="text" name="specialization" class="form-control"
            value="{{ old('specialization', $instructor?->specialization) }}"
            placeholder="e.g. Safety, Leadership, IT">
        @error('specialization')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">Email</label>
        <input type="email" name="email" class="form-control"
            value="{{ old('email', $instructor?->email) }}" placeholder="email@example.com">
        @error('email')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">Phone</label>
        <input type="text" name="phone" class="form-control"
            value="{{ old('phone', $instructor?->phone) }}" placeholder="+255...">
        @error('phone')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">Link to Employee (optional)</label>
        <select name="employee_id" class="form-control instructor-select2-employee">
            <option value="">— None —</option>
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}"
                    @selected(old('employee_id', $instructor?->employee_id) == $employee->id)>
                    {{ $employee->full_name }}
                </option>
            @endforeach
        </select>
        @error('employee_id')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">Status</label>
        <select name="is_active" class="form-control">
            <option value="1" @selected(old('is_active', $instructor?->is_active ?? true))>Active</option>
            <option value="0" @selected(old('is_active', $instructor?->is_active ?? true) == false)>Inactive</option>
        </select>
        @error('is_active')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label fw-medium">Notes</label>
        <textarea name="notes" class="form-control" rows="2"
            placeholder="Additional information">{{ old('notes', $instructor?->notes) }}</textarea>
        @error('notes')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>
