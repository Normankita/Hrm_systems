<div class="col-md-6 position-relative">
    <label class="form-label fw-medium">Employee <span class="text-danger">*</span></label>
    <input type="text" class="form-control" wire:model.live="employee_name"
        wire:click="searchEmployee" wire:keyup="searchEmployee" autocomplete="off"
        placeholder="Search employee...">
    @error('employee_name')
        <small class="text-danger">{{ $message }}</small>
    @enderror
    @error('employee_id')
        <small class="text-danger d-block">{{ $message }}</small>
    @enderror
    @if (!empty($employees))
        <div class="list-group position-absolute w-100 shadow-sm mt-1"
            style="z-index: 1050; max-height: 200px; overflow-y: auto;">
            @foreach ($employees as $employee)
                <button type="button" class="list-group-item list-group-item-action"
                    wire:click="selectEmployee({{ $employee->id }})">
                    {{ $employee->full_name }}
                </button>
            @endforeach
        </div>
    @endif
    <span wire:loading wire:target="searchEmployee" class="spinner-border spinner-border-sm text-primary mt-1"></span>
</div>
