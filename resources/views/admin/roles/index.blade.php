@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body ">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Role Section</h3>
                        <button type="button" class="btn btn-primary  mb-4" data-toggle="modal" data-target="#roles_add">
                            Add Role
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-nowrap">
                            <thead class="table-light text-lime">

                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $key => $role)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary  mb-4" data-toggle="modal"
                                                data-target="#roles_update">
                                                Add Role
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">No employees found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Form Modal -->
    <div class="modal fade" id="roles_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormTitle"
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
                    <form id="dept-submit" method="post" action="{{ route('admin.roles.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="Name">Role Name</label>
                            <input type="text" class="form-control" id="Name" aria-describedby="emailHelp"
                                name="name" placeholder="Enter Name">
                            <div>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
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


    @foreach ($roles as $role)
       <!-- Form Modal -->
       <div class="modal fade" id="roles_update" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormTitle"
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
                   <form id="dept-submit" method="post" action="{{ route('admin.roles.store') }}">
                       @csrf
                       <div class="form-group">
                           <label for="Name">Role Name</label>
                           <input type="text" class="form-control" id="Name" aria-describedby="emailHelp"
                               name="name" placeholder="Enter Name">
                           <div>
                               @error('name')
                                   <span class="text-danger">{{ $message }}</span>
                               @enderror
                           </div>
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
    @endforeach
@endsection
