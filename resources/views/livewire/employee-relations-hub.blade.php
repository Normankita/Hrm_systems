<div>
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <button type="button" class="nav-link {{ $activeTab === 'complaints' ? 'active' : '' }}"
                wire:click="setTab('complaints')">
                <i class="mdi mdi-message-alert me-1"></i> Complaints
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link {{ $activeTab === 'conflicts' ? 'active' : '' }}"
                wire:click="setTab('conflicts')">
                <i class="mdi mdi-account-group me-1"></i> Conflicts
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link {{ $activeTab === 'disciplines' ? 'active' : '' }}"
                wire:click="setTab('disciplines')">
                <i class="mdi mdi-gavel me-1"></i> Disciplines
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link {{ $activeTab === 'resolutions' ? 'active' : '' }}"
                wire:click="setTab('resolutions')">
                <i class="mdi mdi-check-decagram me-1"></i> Resolutions
            </button>
        </li>
    </ul>

    @if ($activeTab === 'complaints')
        <livewire:complaints-table
            :employee-id="$employeeId"
            :allow-manage="$allowManage"
            :personal-mode="$personalMode"
            :require-permission="$requirePermission"
            :key="'complaints-' . ($employeeId ?? 'all') . '-' . (int) $personalMode"
        />
        <livewire:manage-complaint
            :download-route="$downloadRoute"
            :personal-mode="$personalMode"
            :key="'manage-complaint-' . ($employeeId ?? 'all')"
        />
    @elseif ($activeTab === 'disciplines')
        <livewire:disciplines-table
            :employee-id="$employeeId"
            :allow-manage="$allowManage"
            :personal-mode="$personalMode"
            :require-permission="$requirePermission"
            :key="'disciplines-' . ($employeeId ?? 'all')"
        />
        <livewire:manage-discipline
            :download-route="$downloadRoute"
            :personal-mode="$personalMode"
            :key="'manage-discipline-' . ($employeeId ?? 'all')"
        />
    @elseif ($activeTab === 'conflicts')
        <livewire:conflicts-table
            :employee-id="$employeeId"
            :allow-manage="$allowManage"
            :personal-mode="$personalMode"
            :require-permission="$requirePermission"
            :key="'conflicts-' . ($employeeId ?? 'all')"
        />
        <livewire:manage-conflict
            :download-route="$downloadRoute"
            :personal-mode="$personalMode"
            :key="'manage-conflict-' . ($employeeId ?? 'all')"
        />
    @else
        <livewire:resolutions-table
            :employee-id="$employeeId"
            :allow-manage="$allowManage"
            :personal-mode="$personalMode"
            :require-permission="$requirePermission"
            :key="'resolutions-' . ($employeeId ?? 'all')"
        />
        <livewire:manage-resolution
            :download-route="$downloadRoute"
            :employee-id="$employeeId"
            :personal-mode="$personalMode"
            :key="'manage-resolution-' . ($employeeId ?? 'all')"
        />
    @endif
</div>
