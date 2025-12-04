@extends('layouts.system')

@section('content')
    @can('create_payroll')
        <div id="emps">
            <div class="rom justify-content-center">
                <div class="col-4">
                    <div v-if="!pageComplete" class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div v-if="loading" class="col-4">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="d-none" class="row" id="entry">
                <div class="col-12" v-if="showLoader">
                    <div class="row justify-content-center">
                        <div class="col">
                            <div v-if="!pageComplete" class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="!showLoader" class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="mb-0">Select for Payment</h3>
                                <button type="button" v-on:click="submitForPayment" class="btn btn-primary">
                                    Generate for Selected Employees</button>
                            </div>

                            <div class="table-responsive">
                                <span>Total Employees: {{ $employees->count() }}</span>
                                <table class="table dt-table table-bordered table-hover align-middle text-nowrap">
                                    <div>
                                        <label class="px-3" for="all_checker">Select All</label>
                                        <input type="checkbox" id="all-checker" name="all_checker">
                                    </div>
                                    <thead class="table-light text-dark">
                                        <tr>
                                            <th>Select</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Department</th>
                                            <th>Employee Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($employees as $key => $employee)
                                            <tr class="text-dark">
                                                <td>
                                                    <input class="row-checker" type="checkbox" name="selected_employees[]"
                                                        value="{{ $employee->id }}">
                                                </td>
                                                <td>{{ $employee->full_name }}</td>
                                                <td>{{ $employee->phone_number }}</td>
                                                <td>{{ $employee->email }}</td>
                                                <td>{{ $employee->department->name ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge text-dark">
                                                        {{ $employee->employee_type }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">No employees found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection


@section('scripts')
    <script>
        const handler1 = new TableSelectionHandler('.dt-table', '#all-checker');
        const app = Vue.createApp({
            data() {
                return {
                    pageComplete: false,
                    formSubmmitted: false,
                    loading: false
                }
            },
            mounted() {
                console.log('Vue app mounted');
                // on the page done laoding change the d-none in the #emp div
                this.pageComplete = true;
                document.getElementById('entry').classList.remove('d-none');
            },
            computed: {
                // Define any computed properties if needed
                showLoader() {
                    return this.formSubmmitted;
                }
            },
            methods: {
                // Define any methods if needed
                async submitForPayment() {
                    if (confirm('Confirm to disburse ')) {
                        this.loading = true;
                        this.formSubmmitted = true;
                        const selected = handler1.getSelected();
                        const uri = "{{ route('employee.manage.payrolls.generateSelected') }}";
                        const redirectUrl = "{{ route('admin.payrolls.index') }}";
                        console.log(selected, uri);
                        await axios.post(uri, {
                                selected_employees: selected
                            })
                            .then(response => {
                                this.formSubmmitted = false;
                                if (response.data.status === 'success') {
                                    window.location.href = redirectUrl;
                                } else {
                                    this.formSubmmitted = false;
                                    alert(response.data.message);
                                }
                                this.loading = false;
                            })
                            .catch(error => {
                                this.formSubmmitted = false;
                                console.error('Error generating payroll:', error);
                                alert('An error occurred while generating payroll.');
                                this.loading = false;
                            });
                    }
                }
            }
        });
        app.mount('#emps');
    </script>
@endsection
