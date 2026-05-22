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
                                    <!-- Button to Open Modal -->
                                    <button type="button" class="btn btn-primary btn-sm"
                                        v-on:click="showChooseGropsModel">
                                        <i class="mdi mdi-file-excel"></i>
                                        Disburse to
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
                            <table
                                class="table dt-table cat-table table-bordered
                                table-hover align-middle text-nowrap">
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

        <!-- Modal Structure -->
        <div class="modal fade" id="choose-groups" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Disburse</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="col-md-11">
                                <h4>Choose Groups</h4>
                                <table
                                    class="table dt-table group-table table-bordered
                                table-hover align-middle text-nowrap">
                                    <label for="all">select all</label>
                                    <input class="all-group-checker m-2" type="checkbox" name="all">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Group</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($groups as $group)
                                            <tr>
                                                <td>
                                                    <input class="group-checker" type="checkbox"
                                                        value="{{ $group->id }}">
                                                </td>
                                                <td>{{ $group->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div clas="col-md-11">
                                <h4>Selected Categories</h4>
                                <ol>
                                    <li v-for="category in selectedCategories" :key="category.id">
                                        <p class="lead">
                                            @{{ category.name }}
                                        </p>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button v-on:click="handleDisbursement" type="button" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-file-excel"></i>
                            Disburse to selected
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection


@section('scripts')
    <script>
        // Initialize the handler and store the instance
        const handler1 = new TableSelectionHandler('.cat-table', '.all-checker');
        const handler2 = new TableSelectionHandler('.group-table', '.all-group-checker');

        const vue = Vue.createApp({
            data() {
                return {
                    choiceCategories: @json($categories),
                    selectedCategories: []
                };
            },
            methods: {
                showChooseGropsModel() {
                    const selectedCategory = handler1.getSelected();
                    if (selectedCategory.length === 0) {
                        alert("No category selected.");
                        return;
                    }
                    this.selectedCategories = this.choiceCategories.filter(
                        category => selectedCategory.includes(category.id.toString()));
                    $('#choose-groups').modal('show');
                },
                async handleDisbursement() {
                    const selectedCategory = handler1.getSelected();
                    const selectedGroups = handler2.getSelected();
                    // sending axios request to route
                    if (true) {
                        const request = await axios.post("{{ route('disbursements.disburse') }}", {
                            categories: selectedCategory,
                            groups: selectedGroups,
                            basedOn: 'groupCategory',
                        });
                        if (request.status === 200) {
                            // Handle success response
                            console.log('Disbursement request sent successfully');
                            // Optionally, you can redirect or show a success message
                            window.location.href = "{{ route('admin.disbursements.index') }}";
                        } else {
                            // Handle error response
                            console.error('Error sending disbursement request', request);
                        }
                    }
                }
            }
        }).mount('#emps');
    </script>
@endsection
