<div class="position-relative">
    {{-- Search Input --}}
    <input type="text" class="form-control" placeholder="Search employee..." wire:model.live="query">

    {{-- Hidden input so the form sends employee_id --}}
    <input type="hidden" name="employee_id" value="{{ $selectedEmployeeId }}">


    {{-- Search Results --}}
    @if (!empty($employees))
        <ul class="list-group position-absolute w-100" style="z-index: 1000;">
            @foreach ($employees as $employee)
                <li class="list-group-item list-group-item-action" wire:click="selectEmployee({{ $employee->id }})">
                    {{ $employee->full_name }}
                </li>
            @endforeach
        </ul>
    @endif

    {{-- Selected Employee --}}
    @if ($selectedEmployee)
        <div class="mt-2">
            <strong>Selected:</strong> {{ $selectedEmployee->full_name }}
        </div>
    @endif

    @error('employee_id')
        <div class="text-danger mt-2">
            {{ $message }}
        </div>
    @enderror
</div>
