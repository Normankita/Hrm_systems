@extends('layouts.system')

@section('content')
    @can('view_allowances')
        <div class="row" id="appAllowances">
            <div class="col-12">
                <div class="card">
                    <div class="card-body ">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="mb-0">Allowance Directory</h3>
                            @can('create_allowances')
                                <a href="{{ route('admin.allowances.create') }}" class="btn btn-primary">Add New Category</a>
                            @endcan
                        </div>

                        <div class="table-responsive">
                            <span>Total allowances: {{ $allowances->count() }}</span>
                            <table class="table table-bordered table-hover align-middle text-nowrap">
                                <thead class="table-light text-dark">

                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Taxable</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($allowances as $key => $allowance)
                                        <tr class="text-dark">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $allowance->name }}</td>
                                            <td>{{ $allowance->description }}</td>
                                            <td>
                                                <span class="badge text-dark">
                                                    {{ $allowance->is_taxable ? 'Yes' : 'No' }}
                                                </span>
                                            </td>
                                            <td>
                                                {{-- <x-system.btn-view :key="$key" :route="route('admin.allowances.show', $allowance->id)" /> --}}
                                                @can('edit_allowances')
                                                    <button v-on:click="openModal({{ $allowance->id }})" type="button"
                                                        class="btn btn-outline-dark btn-sm p-0 px-1 mx-1 mdi mdi-pencil">
                                                        edit
                                                    </button>
                                                @endcan
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">No allowances found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="editmodel" tabindex="-1" role="dialog" aria-labelledby="editmodelLabel"
                            aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editmodelLabel">Modal title</h5>
                                        <button v-on:click="closeModal()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editAllowanceForm">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="name">Name</label>
                                                    <input type="text" name="name" class="form-control" v-model="formData.name">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="description">Description</label>
                                                    <textarea name="description" class="form-control" v-model="formData.description"></textarea>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button v-on:click="closeModal()" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button v-on:click="editAllowance()" type="button" class="btn btn-primary">
                                            <span v-if="isLoading">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                Loading...
                                            </span>
                                            <span v-else>
                                                Save changes
                                            </span>
                                        </button>
                                    </div>
                                </div>
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
        const app = Vue.createApp({
            data() {
                return {
                    isLoading: false,
                    allowances: @json($allowances->values()->toArray()),
                    selectedAllowance: null,
                    formData: {
                        name: '',
                        description: ''
                    }
                }
            },
            methods: {
                editAllowance() {
                    this.isLoading = true;  
                    try {
                        const uri = "{{ route('update.allowance', $allowance->id) }}";
                        axios.put(uri, this.formData).then(response => {
                            alert('Allowance updated successfully');
                            location.reload();
                        }).catch(error => {
                            alert('An error occurred while updating the allowance');
                            console.log(error);
                        });
                    } catch (error) {
                        console.log(error);
                        alert('An error occurred while updating the allowance');
                        this.isLoading = false;
                    }
                },
                closeModal() {
                    var modal = $('#editmodel');
                    modal.modal('hide');
                },
                openModal(id) {
                    this.selectedAllowance = this.allowances.find(allowance => allowance.id === id);
                    if (!this.selectedAllowance) {
                        alert('Allowance not found');
                        return;
                    }
                    this.formData.name = this.selectedAllowance.name;
                    this.formData.description = this.selectedAllowance.description;
                    var modal = $('#editmodel');
                    modal.modal('show');
                }
            }
        })
        app.mount('#appAllowances');
    </script>
@endsection
