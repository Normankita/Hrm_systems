@extends('layouts.system')


@section('content')
    @canany(['edit_allowances', 'view_allowances', 'create_allowances'])
        <div class="row">
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

                        <x-system.modal id="createAllowanceGroupModal" form="createAllowanceGroupForm"
                            title="New Allowance Group" :inside="true">
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
                                        <textarea name="description" step="0.01" class="form-control">{{old('description')}}</textarea>
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
                                    <td>{{  $group->name }}</td>
                                    <td>
                                        <!-- make the group desc less than 50 words -->
                                        {{ Str::limit($group->description, 50) }}
                                    </td>
                                    <td>{{ $group->employees()->count() }}</td>
                                    <td>
                                        <x-system.btn-view text="view" route="{{ route('admin.employee.allowances.groups.edit', $group->id) }}" />
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endcanany
@endsection
