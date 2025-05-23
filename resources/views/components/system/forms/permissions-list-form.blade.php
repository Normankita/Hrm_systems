@props(['grouped', 'employee' => null, 'role' => null])


@foreach ($grouped as $groupName => $loopPermissions)
    <div class="permission-group">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <h5 class="mb-0">{{ ucfirst($groupName) }}</h5>
            <div class="form-check">
                <input type="checkbox" class="form-check-input group-select-all" id="select-all-{{ $groupName }}"
                    data-group="{{ $groupName }}">
                <label class="form-check-label" for="select-all-{{ $groupName }}">Select
                    All</label>
            </div>
        </div>
        @foreach ($loopPermissions as $permission)
            @php
                if ($role) {
                    $class = $role->hasPermissionTo($permission->name);
                } else {
                    $class =
                        $employee->user->hasPermissionTo($permission->name);
                }
            @endphp
            <label class="permission-box  {{ $class ? 'selected' : '' }}">
                <div>
                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                        {{ $class ? 'checked' : '' }}>
                    {{ $permission->slug }}
                    <i class="bi bi-check-circle check-icon"></i>
                </div>

            </label>
        @endforeach
    </div>
@endforeach
