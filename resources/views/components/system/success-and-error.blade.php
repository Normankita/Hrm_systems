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
    

    @if(session('warning'))
        <x-system.warning-alert :message="session('warning')">
        </x-system.warning-alert>

    @endif
   
</div>
