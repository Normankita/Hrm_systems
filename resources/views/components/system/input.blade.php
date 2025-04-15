<div>
    <!-- receive props from the parent component -->
    @props(['name', 'type' => 'text', 'placeholder' => ''])
    <!-- Simplicity is the ultimate sophistication. - Leonardo da Vinci -->
    <input type="text" class="form-control form-control-lg form-control-secondary rounded-0"
        placeholder="{{ $placeholder }}"
        name="{{ $name }}">
</div>
