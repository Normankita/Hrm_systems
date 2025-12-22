@extends('layouts.system')
@section('content')
    <div id="app">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body ">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="mb-0">Frequency Directory</h3>
                            @hasrole('ADMIN')
                                <div class="col-md-6 mt-2">
                                    <x-system.modal-button class="btn btn-block btn-primary btn-custom me-2"
                                        data-bs-toggle="modal" id="AddFrequency" text="Add a New Frequency" />
                                </div>
                            @endhasrole
                        </div>
                        <div class="table-responsive">
                            <span>Total frequencies: {{ $frequencies->count() }}</span>
                            <table class="table table-bordered table-hover align-middle text-nowrap">
                                <thead class="table-light text-dark">

                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>based on</th>
                                        <th>Count per Category</th>
                                        <th>Day span</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($frequencies as $key => $frequency)
                                        <tr class="text-dark">
                                            <td>{{ $loop->iteration }} - {{ $frequency->id }}</td>
                                            <td>{{ $frequency->name }}</td>
                                            <td>{{ $frequency->base_category }}</td>
                                            <td>{{ $frequency->no_times }}</td>
                                            <td>{{ $frequency->days_apart }}</td>
                                            <td>
                                                {{-- <x-system.btn-view :key="$key" :route="route('employee.manage.frequencies.show', $allowance->id)" /> --}}
                                                <button class="btn btn-outline-dark btn-sm p-0 px-1 mx-1 mdi mdi-pencil"
                                                    v-on:click="openEditFrequencyModal({{ $frequency->id }})">&nbsp Edit
                                                    &nbsp
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">No frequencies found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-system.modal id="AddFrequency" form="Add a New Frequency" :inside="true">
            <form action="{{ route('admin.frequencies.store') }}" id="AddFrequencyForm" method="POST">
                @csrf
                <div class="form-group row px-3">
                    <div class="col-md-12 mb-4">
                        <label for="name" class="text-dark font-weight-medium">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-4">
                        <label for="effective_date" class="text-dark font-weight-medium">based on</label>
                        <select name="base_category" id="base_category" class="form-control" required>
                            <option value="year">Year</option>
                            <option value="month">Month</option>
                            <option value="week">Week</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-4">
                        <label for="no_base_times" class="text-dark font-weight-medium">based on (count)</label>
                        <input name="no_base_times" id="no_base_times" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-4">
                        <label for="no_times" class="text-dark font-weight-medium">Number of times per cycle</label>
                        <input name="no_times" id="no_times" class="form-control" required>
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </x-system.modal>



        <div class="modal fade" id="editFrequecyModal" v-if="showEditFrequencyModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalFormTitle" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalFormTitle">Edit @{{ selectedFrequency.name }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form v-if="selectedFrequency" id="EditFrequencyForm">
                                    <div class="form-group row px-3">
                                        <div class="col-md-12 mb-4">
                                            <label for="name" class="text-dark font-weight-medium">Name</label>
                                            <input type="text" name="name" class="form-control"
                                                v-model="selectedFrequency.name" required>
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <label for="effective_date" class="text-dark font-weight-medium">
                                                based on</label>
                                            <select name="base_category" v-model="selectedFrequency.base_category"
                                                id="base_category" class="form-control" required>
                                                <option value="year">
                                                    Year
                                                </option>
                                                <option value="month">
                                                    Month
                                                </option>
                                                <option value="week">
                                                    Week
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <label for="no_base_times" class="text-dark font-weight-medium">based on
                                                (count)</label>
                                            <input name="no_base_times" id="no_base_times" class="form-control"
                                                v-model="selectedFrequency.no_base_times" required>
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <label for="no_times" class="text-dark font-weight-medium">Number of times per
                                                cycle</label>
                                            <input name="no_times" id="no_times" class="form-control"
                                                v-model="selectedFrequency.no_times" required>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button v-on:click="editFrequencySubmit" type="submit" class="btn btn-primary btn-pill">Save
                            Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        // use vue 3 to create vue object
        const app = Vue.createApp({
            data() {
                return {
                    frequencies: @json($frequencies->values()->toArray()),
                    showEditFrequencyModal: false,
                    selectedFrequency: null,
                }
            },
            methods: {
                openEditFrequencyModal(id) {
                    // bind the selected object based on id
                    console.log('Frequencies:', this.frequencies);
                    this.selectedFrequency = this.frequencies.find(frequency => frequency.id === id);
                    this.showEditFrequencyModal = true;
                    $('#editFrequecyModal').modal('show');
                },
                closeEditFrequencyModal() {
                    this.showEditFrequencyModal = false;
                    this.selectedFrequency = null;
                    $('#editFrequecyModal').modal('hide');
                },
                editFrequencySubmit() {
                    // submit the form
                    const uri = "{{  route('update.frequency') }}";
                    axios.put(uri, this.selectedFrequency)
                        .then(response => {
                            // handle success
                            console.log(response);
                            this.closeEditFrequencyModal();
                            // reload the page
                            location.reload();
                        })
                        .catch(error => {
                            // handle error
                            console.log(error);
                        });
                }
            },
            mounted() {

            }
        }).mount('#app');
    </script>
@endsection
