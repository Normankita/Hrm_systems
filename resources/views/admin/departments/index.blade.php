@extends('layouts.system')


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-system.success-and-error />
                    <div class="col-lg-6 col-xl-4">
                        <button type="button" class="btn btn-info btn-pill mb-4" data-toggle="modal"
                            data-target="#exampleModalForm">
                            Add Department
                        </button>
                    </div>
                    <h4 class="card-title">Departments</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-nowrap">
                            <thead class="table-light text-lime">
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Created At</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departments as $department)
                                    <tr>
                                        <td>{{ $department->name }}</td>
                                        <td>{{ $department->code }}</td>
                                        <td>{{ $department->description }}</td>
                                        <td>{{ \Carbon\Carbon::parse($department->created_at)->format('d M Y') }}</td>
                                        <td>
                                            <a data-toggle="modal" data-target="#editDepartment">
                                                <i class="mdi mdi-pencil-off"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- Form Modal -->
        <div class="modal fade" id="exampleModalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormTitle"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalFormTitle">Add Department</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="dept-submit" method="post" action="{{ route('admin.departments.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="Name">Department Name</label>
                                <input type="text" class="form-control" id="Name" aria-describedby="emailHelp"
                                    name="name" placeholder="Enter Name">
                            </div>
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text" class="form-control" id="code" aria-describedby="emailHelp"
                                    name="code" placeholder="Enter Code">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" rows="5" aria-describedby="emailHelp" name="description"
                                    placeholder="Enter Description"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-pill" data-dismiss="modal">Close</button>
                        <button type="submit" form="dept-submit" class="btn btn-primary btn-pill">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>


        @foreach ($departments as $department)
            <!-- Form Modal -->
            <div class="modal fade" id="editDepartment" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalFormTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalFormTitle">Update Department</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="dept-submit-{{ $department->id }}" method="post"
                                action="{{ route('admin.departments.update', $department->id) }}">
                                @csrf
                                @method('patch')
                                <div class="form-group">
                                    <label for="Name">Department Name</label>
                                    <input type="text" class="form-control" id="Name" aria-describedby="emailHelp"
                                        value="{{ $department->name }}" name="name" placeholder="Enter Name">
                                    @error('name')
                                        <span>{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="code">Code</label>
                                    <input type="text" class="form-control" id="code"
                                        value="{{ $department->code }}" aria-describedby="emailHelp" name="code"
                                        placeholder="Enter Code">
                                    @error('code')
                                        <span>{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" rows="5" aria-describedby="emailHelp" name="description"
                                        placeholder="Enter Description">{{ $department->description }}</textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-pill" data-dismiss="modal">Close</button>
                            <button type="submit" form="dept-submit-{{ $department->id }}"
                                class="btn btn-primary btn-pill">update</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endsection


    @section('scripts')
        <script>
            /*======== 14. DATA TABLE ========*/
            var productsTable = $("#productsTable");
            if (productsTable.length != 0) {
                productsTable.DataTable({
                    info: false,
                    lengthChange: false,
                    lengthMenu: [
                        [5, 10, 15, -1],
                        [5, 10, 15, "All"],
                    ],
                    scrollX: true,
                    order: [
                        [2, "asc"]
                    ],
                    columnDefs: [{
                        orderable: false,
                        targets: [, 0, 6, -1],
                    }, ],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search...",
                    },
                });
            }
        </script>
    @endsection
