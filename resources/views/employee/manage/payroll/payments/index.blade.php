@extends('layouts.system')

@section('content')
    <div class="row" id="page">
        <div class="col-12">
            <x-system.tables.payroll-table-card
               :backRoute="$backRoute" :payrolls="$payrolls" title='All' />
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
                methods: {
                    approveSelected() {
                        if (confirm('Confirm to disburse ')) {
                            this.formSubmmitted = true;
                            const selected = handler1.getSelected();
                            const uri = "{{ route('employee.manage.payrolls.approveSelected') }}";
                            console.log(selected, uri);
                            axios.post(uri, {
                                    selected_payrolls: selected
                                })
                                .then(response => {
                                    this.formSubmmitted = false;
                                    if (response.data.status === 'success') {
                                        alert(response.data.message);
                                        window.location.reload();
                                    } else {
                                        this.formSubmmitted = false;
                                        alert(response.data.message);
                                    }
                                })
                                .catch(error => {
                                    this.formSubmmitted = false;
                                    console.error('Error generating payroll:', error);
                                });
                        }
                    }
                }
            });
            app.mount('#page');
        });
    </script>
@endsection
