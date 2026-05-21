{{-- Shared contract form fields for create & edit --}}
<div class="mb-4">
    <h6 class="text-uppercase text-muted fw-semibold small mb-3">
        <i class="bi bi-person-badge me-1"></i> Employee
    </h6>
    <div class="row g-3">
        <div class="col-md-6 position-relative">
            <label class="form-label fw-medium">Employee Name <span class="text-danger">*</span></label>
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
        <div class="col-md-6">
            <label class="form-label fw-medium">Contract Number</label>
            <input type="text" class="form-control bg-light"
                value="{{ $contract_number ?? 'Auto-generated on save' }}" readonly disabled>
        </div>
    </div>
</div>

<hr class="text-muted opacity-25">

<div class="mb-4">
    <h6 class="text-uppercase text-muted fw-semibold small mb-3">
        <i class="bi bi-file-earmark-text me-1"></i> Contract Details
    </h6>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label fw-medium">Contract Type <span class="text-danger">*</span></label>
            <select class="form-select" wire:model="contract_type">
                <option value="">-- Select --</option>
                @foreach (\App\Enums\ContractEnum::cases() as $type)
                    <option value="{{ $type->value }}">{{ $type->name }}</option>
                @endforeach
            </select>
            @error('contract_type')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
            <select class="form-select" wire:model="contract_status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="terminated">Terminated</option>
                <option value="expired">Expired</option>
            </select>
            @error('contract_status')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-medium">Work Location</label>
            <input type="text" class="form-control" wire:model="work_location" placeholder="e.g. Head Office">
            @error('work_location')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-12">
            <label class="form-label fw-medium">Termination Reason</label>
            <textarea class="form-control" rows="2" wire:model="termination_reason"
                placeholder="Required if contract is terminated"></textarea>
            @error('termination_reason')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
    </div>
</div>

<hr class="text-muted opacity-25">

<div class="mb-4">
    <h6 class="text-uppercase text-muted fw-semibold small mb-3">
        <i class="bi bi-calendar3 me-1"></i> Dates
    </h6>
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label fw-medium">Start Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" wire:model="start_date">
            @error('start_date')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-medium">End Date</label>
            <input type="date" class="form-control" wire:model="end_date">
            @error('end_date')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-medium">Probation End</label>
            <input type="date" class="form-control" wire:model="probation_end_date">
            @error('probation_end_date')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-medium">Signed Date</label>
            <input type="date" class="form-control" wire:model="signed_date">
            @error('signed_date')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
    </div>
</div>

<hr class="text-muted opacity-25">

<div class="mb-4">
    <h6 class="text-uppercase text-muted fw-semibold small mb-3">
        <i class="bi bi-cash-stack me-1"></i> Compensation
    </h6>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label fw-medium">Basic Salary</label>
            <input readonly disabled type="number" step="0.01" min="0" class="form-control" 
                wire:model="basic_salary" placeholder="0.00">
            @error('basic_salary')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-medium">Currency</label>
            <input type="text" class="form-control" wire:model="currency" placeholder="TZS">
            @error('currency')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-medium">Payment Frequency</label>
            <select class="form-select" wire:model="payment_frequency">
                <option value="">-- Select --</option>
                <option value="Monthly">Monthly</option>
                <option value="Weekly">Weekly</option>
                <option value="Bi-Weekly">Bi-Weekly</option>
                <option value="Annual">Annual</option>
            </select>
            @error('payment_frequency')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
    </div>
</div>
