@extends('layouts.system')

@section('content')
    @can('view_allowances')
        <div class="row">
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
                                                    <x-system.btn-edit :key="$key" :route="route('admin.allowances.edit', $allowance->id)" />
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
                    </div>
                </div>
            </div>
        </div>
    @endcan


@endsection
