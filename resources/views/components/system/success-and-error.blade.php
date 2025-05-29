<div>
    <!-- message to show the session data of success and fail -->
    @if (session('success'))
        <x-system.success-alert :message="session('success')">
        </x-system.success-alert>

    @endif

    @if (session('error'))
        <x-system.fail-alert :message="session('error')">
        </x-system.fail-alert>
    @endif

    @if (session('fail'))
        <x-system.fail-alert :message="session('fail')">
        </x-system.fail-alert>
    @endif

    @if(session('message'))
    <div class="alert alert-{{ session('status') == 'success' ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


    @if(session('warning'))
        <x-system.warning-alert :message="session('warning')">
        </x-system.warning-alert>

    @endif

</div>

