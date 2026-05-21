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
        <livewire:complaints-table />
        <livewire:manage-complaint />
    @elseif ($activeTab === 'disciplines')
        <livewire:disciplines-table />
        <livewire:manage-discipline />
    @elseif ($activeTab === 'conflicts')
        <livewire:conflicts-table />
        <livewire:manage-conflict />
    @else
        <livewire:resolutions-table />
        <livewire:manage-resolution />
    @endif
</div>
