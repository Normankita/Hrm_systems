@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-12">
            <x-system.tables.payroll-table-card :payrolls="$payrolls" title='Rejected'/>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            const handler1 = new TableSelectionHandler('.dtp-table', '#all-checker');
            const app = Vue.createApp({
                data() {
                    return {
                        pageComplete: false,
                        formSubmmitted: false
                    }
                },
                mounted() {
                    this.pageComplete = true;
                    document.getElementById('main').classList.remove('d-none');
                },
                computed: {
                    showLoader() {
                        return this.formSubmmitted;
                    }
                },
            });
            app.mount('#page');
        });
    </script>
@endsection
