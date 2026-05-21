<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('show-modal', ({ modalId }) => {
            const el = document.getElementById(modalId);
            if (el) bootstrap.Modal.getOrCreateInstance(el).show();
        });
        Livewire.on('hide-modal', ({ modalId }) => {
            const el = document.getElementById(modalId);
            const modal = el ? bootstrap.Modal.getInstance(el) : null;
            if (modal) modal.hide();
        });
    });
</script>
