<style>
    .select2-container--default .select2-selection--multiple {
        min-height: 38px;
        border: 1px solid #e0e0e0;
        border-radius: 0.25rem;
        padding: 2px 6px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #4c84ff;
        border: none;
        color: #fff;
        border-radius: 4px;
        padding: 2px 8px;
        margin-top: 4px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: rgba(255, 255, 255, 0.85);
        margin-right: 4px;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #4c84ff;
    }

    .modal .select2-container {
        width: 100% !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function initTrainingSelect2($container) {
        $container.find('.training-select2-departments').each(function() {
            const $el = $(this);
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.select2('destroy');
            }
            $el.select2({
                placeholder: 'Select one or more departments',
                allowClear: true,
                width: '100%',
                dropdownParent: $container.closest('.modal').length ? $container.closest('.modal') : $(document.body)
            });
        });

        $container.find('.training-select2-instructors').each(function() {
            const $el = $(this);
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.select2('destroy');
            }
            $el.select2({
                placeholder: 'Select registered instructors',
                allowClear: true,
                width: '100%',
                dropdownParent: $container.closest('.modal').length ? $container.closest('.modal') : $(document.body)
            });
        });
    }

    $(document).ready(function() {
        initTrainingSelect2($(document.body));

        $('.modal').on('shown.bs.modal', function() {
            initTrainingSelect2($(this));
        });
    });
</script>
