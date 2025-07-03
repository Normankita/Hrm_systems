@extends('layouts.system')

@section('content')
    <div class="row justify content-center" id="emps">
        <div class="col-12">
            <!-- Card for displaying three catregories of allawances
                                            which are group, individual, category allowances -->
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="col-12">
                            <div>
                                <div class="form-group">
                                    <button v-on:click="handleDisbursement" type="button"
                                    class="btn btn-secondary">
                                        <i class="mdi mdi-file-excel"></i>
                                        Disburse to selected
                                    </button>
                                </div>
                            </div>

                            <div class="mt-4 mb-4">
                                <h3 class="text-dark font-weight-bold">Select Desired Category</h3>
                                <p class="text-muted">
                                    All Employees that are Eligible in the selected Allawance will be Disbursed.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table class="table dt-table table-bordered table-hover align-middle text-nowrap">
                                <label for="all">select all</label>
                                <input class="all-checker m-2" type="checkbox" name="all">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>
                                                <input class="row-checker" type="checkbox" value="{{ $category->id }}">
                                            </td>
                                            <td>{{ $category->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        // Initialize the handler and store the instance
        const handler1 = new TableSelectionHandler('.dt-table', '.all-checker');

        const vue = Vue.createApp({
            data() {
                return {
                };
            },
            methods: {
                async handleDisbursement() {
                    const selectedCategory = handler1.getSelected();
                    // sending axios request to route
                    const request = await axios.post("{{ route('employee.manage.disbursements.store') }}", {
                        categories: selectedCategory,
                        basedOn: 'category',
                    });
                    if (request.status === 200) {
                        // Handle success response
                        console.log('Disbursement request sent successfully');
                        // Optionally, you can redirect or show a success message
                        window.location.href = "{{ route('employee.manage.disbursements.index') }}";
                    } else {
                        // Handle error response
                        console.error('Error sending disbursement request', request);
                    }

                }
            }
        }).mount('#emps');
    </script>
@endsection
