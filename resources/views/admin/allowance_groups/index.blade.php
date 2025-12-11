@extends('layouts.system')


@section('content')
    @canany(['edit_allowances', 'view_allowances', 'create_allowances'])
        <div class="row" id="myApp">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-start">
                        <div class="col-md-6">
                            <h3 class="card-title lead" style="text-transform: capitalize;">
                                Allowance Groups
                            </h3>
                        </div>

                    </div>
                    {{-- Create Allowance --}}
                    @can('create_allowances')
                        <x-system.modal-button id="createAllowanceGroupModal" form="createAllowanceGroupForm" title="Create Allowance"
                            text="New Group" />

                        <x-system.modal id="createAllowanceGroupModal" form="createAllowanceGroupForm" title="New Allowance Group"
                            :inside="true">
                            <form action="{{ route('admin.employee.allowances.groups.store') }}" method="POST"
                                id="createAllowanceForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="name" class="form-label">
                                            Name
                                        </label>
                                        <input type="text" name="name" step="0.01" class="form-control" required
                                            value="{{ old('name') }}">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="name" class="form-label">
                                            Description
                                        </label>
                                        <textarea name="description" step="0.01" class="form-control">{{ old('description') }}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-sm btn-primary" type="submit">submit</button>
                                    </div>
                                </div>
                            </form>
                        </x-system.modal>
                    @endcan

                    {{-- Allowances Table --}}
                    <div class="table-responsive mt-4">
                        <table class="table dt-table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>No: Emp</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groups as $key => $group)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $group->name }}</td>
                                        <td>
                                            <!-- make the group desc less than 50 words -->
                                            {{ Str::limit($group->description, 50) }}
                                        </td>
                                        <td>{{ $group->employees()->count() }}</td>
                                        <td>
                                            <x-system.btn-view text="view"
                                                route="{{ route('admin.employee.allowances.groups.edit', $group->id) }}" />
                                            <button v-on:click="editAllowanceGroup({{ $group->id }})"
                                                class="btn btn-outline-dark btn-sm p-0 px-1 mx-1 mdi mdi-pencil">&nbsp
                                                EDIT
                                                &nbsp
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div v-if="selectedGroup" class="modal fade" id="editGroup" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                Edit <b>@{{ selectedGroup.name }}</b>' Details
                            </h5>
                            <button type="button" class="close" aria-label="Close" v-on:click="closeModal()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <form method="POST" id="editAllowanceGroupForm">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="name" class="form-label">
                                                    Name
                                                </label>
                                                <input type="text" name="name" step="0.01" class="form-control"
                                                    required v-model="formData.name">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="name" class="form-label">
                                                    Description
                                                </label>
                                                <textarea name="description" step="0.01" class="form-control" v-model="formData.description"></textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" v-on:click="closeModal()">Close</button>
                            <button v-on:click="updateGroup" type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcanany
@endsection

@section('scripts')
    <script>
        const app = Vue.createApp({
            data() {
                return {
                    groups: @json($groups->values()->toArray()),
                    selectedGroup: null,
                    formData: {
                        name: '',
                        description: ''
                    }
                }
            },
            methods: {
                closeModal() {
                    var modal = $('#editGroup');
                    modal.modal('hide');
                },
                editAllowanceGroup(id) {
                    this.selectedGroup = this.groups.find(g => g.id === id);
                    this.formData.name = this.selectedGroup.name;
                    this.formData.description = this.selectedGroup.description;
                    // redirect to edit page
                    var modal = $('#editGroup');
                    modal.modal('show');
                },
                updateGroup() {
                    const uri = "{{ route('groups.update.group.details') }}";
                    NProgress.start();
                    axios.put(uri, {
                        id: this.selectedGroup.id,
                        name: this.formData.name,
                        description: this.formData.description
                    }).then(response => {
                        // update the groups array
                        location.reload();
                        alert(
                            'Allowance Group Updated Successfully',
                        );
                    }).catch(error => {
                        console.log(error);
                        alert(
                            'An error occurred while updating the group',
                        );
                    }).finally(() => {
                        NProgress.done();
                    });
                }
            }
        });

        app.mount('#myApp');
    </script>
@endsection
