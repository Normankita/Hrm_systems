@extends('layouts.system')
@section('content')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body ">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="mb-0">Frequency Directory</h3>
                                @hasrole('EMPLOYEE')
                                    <div class="col-md-6 mt-2">
                                        <x-system.modal-button class="btn btn-block btn-primary btn-custom me-2" data-bs-toggle="modal"
                                            id="AddFrequency" text="Add a New Frequency" />
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
                                        <th>Base Category</th>
                                        <th>Count per Category</th>
                                        <th>Day span</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($frequencies as $key => $frequency)
                                        <tr class="text-dark">
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $frequency->name }}</td>
                                            <td>{{ $frequency->base_category }}</td>
                                            <td>{{ $frequency->no_times }}</td>
                                            <td>{{ $frequency->days_apart }}</td>
                                            <td>
                                                {{-- <x-system.btn-view :key="$key" :route="route('employee.manage.frequencies.show', $allowance->id)" /> --}}
                                                @can('edit_frequencies')
                                                    <x-system.btn-edit :key="$key" :route="route(
                                                        'employee.manage.frequencies.edit',
                                                        $allowance->id,
                                                    )" />
                                                @endcan
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
            <form action="{{ route('employee.manage.frequencies.store') }}" id="AddFrequencyForm" method="POST">
                @csrf
                <div class="form-group row px-3">
                    <div class="col-md-12 mb-4">
                        <label for="name" class="text-dark font-weight-medium">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-4">
                        <label for="effective_date" class="text-dark font-weight-medium">Base Category</label>
                        <select name="base_category" id="base_category" class="form-control" required>
                            <option value="year">Year</option>
                            <option value="month">Month</option>
                            <option value="week">Week</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-4">
                        <label for="no_base_times" class="text-dark font-weight-medium">Base Category count</label>
                        <input name="no_base_times" id="no_base_times" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-4">
                        <label for="no_times" class="text-dark font-weight-medium">Number of times per base category count</label>
                        <input name="no_times" id="no_times" class="form-control" required>
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </x-system.modal>
@endsection
