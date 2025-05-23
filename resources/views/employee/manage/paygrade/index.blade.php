@extends("layouts.system")

@section('content')
    @can('view_paygrade')
        <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body ">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">PayGrades</h3>
                    </div>
                    <!-- create a create button right here -->
                    @can('create_paygrade')
                        <x-system.modal-button class="btn btn-primary mb-3" id="createPayGrade" text="Create PayGrade" />

                    <x-system.modal size="modal-lg" id="createPayGrade" title="Create PayGrade" form="createPayGradeForm">
                        <form id="createPayGradeForm" action="{{ route('employee.manage.paygrades.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name">PayGrade Name</label>
                                    <input type="text" value="{{ old('name') }}" class="form-control" name="name" id="name"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="name">PayGrade base Salary</label>
                                    <input type="number" value="{{ old('base_salary') }}" class="form-control"
                                        name="base_salary" id="base_salary" required>
                                </div>
                                <div class="form-group">
                                    <label for="name">PayGrade Max Salary</label>
                                    <input type="number" value="{{ old('max_salary') }}" class="form-control"
                                        name="max_salary" id="max_salary" required>
                                </div>

                                <div class="form-group">
                                    <label for="name">PayGrade base Month count</label>
                                    <input type="number" value="{{ old('base_month_count') }}" class="form-control"
                                        name="base_month_count" id="base_month_count" required>
                                </div>

                                <div class="form-group">
                                    <label for="description">PayGrade description</label>
                                    <textarea class="form-control" name="description">
                                                    {{ old('description') }}</textarea>
                                </div>
                            </div>
                        </form>
                    </x-system.modal>
                    @endcan

                    <div class="table-responsive">
                        <span>Total PayGrades: {{ $pay_grades->count() }}</span>
                        <table class="table table-bordered table-hover align-middle text-nowrap">
                            <thead class="table-light text-dark">

                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Base Salary</th>
                                    <th>Max Salary</th>
                                    <th>Month count</th>
                                    <th>description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pay_grades as $key => $pay_grade)
                                    <tr class="text-dark">
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $pay_grade->name }}</td>
                                        {{-- <td>{{ \Carbon\Carbon::parse($pay_grade->date_of_birth)->format('d M Y') }}</td>
                                        --}}
                                        <td>{{ $pay_grade->base_salary }}</td>
                                        <td>{{ $pay_grade->max_salary }}</td>
                                        <td>{{ $pay_grade->base_month_count ?? 'N/A' }}</td>
                                        <td>{{ $pay_grade->description ?? 'N/A' }} </td>
                                        <td class="d-flex p-1 gap-1">
                                            {{-- View Button --}}
                                            <x-system.modal-button class="btn btn-outline-dark p-1  btn-sm mdi mdi-eye-outline"
                                                id="viewPayGrade{{ $pay_grade->id }}" text="View" textColor="" />

                                            {{-- Edit Button --}}
                                            @can('edit_paygrade')
                                                <x-system.modal-button class="btn btn-outline-dark btn-sm p-1 mx-1 mdi mdi-pencil"
                                                id="editPayGrade{{ $pay_grade->id }}" text="Edit" textColor="" />
                                            @endcan
                                        </td>

                                    </tr>
                                    <x-system.modal size="modal-lg" id="viewPayGrade{{ $pay_grade->id }}"
                                        title="Pay Grade Details">
                                        <div class="card shadow-sm border-0">
                                            <div class="card-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-4 font-weight-bold">Name:</div>
                                                    <div class="col-md-8">{{ $pay_grade->name }}</div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 font-weight-bold">Base Salary:</div>
                                                    <div class="col-md-8">{{ number_format($pay_grade->base_salary, 2) }}</div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 font-weight-bold">Max Salary:</div>
                                                    <div class="col-md-8">{{ number_format($pay_grade->max_salary, 2) }}</div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 font-weight-bold">Month Count:</div>
                                                    <div class="col-md-8">{{ $pay_grade->base_month_count }}</div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 font-weight-bold">Description:</div>
                                                    <div class="col-md-8">{{ $pay_grade->description ?? 'N/A' }}</div>
                                                </div>
                                            </div>

                                        </div>
                                    </x-system.modal>

                                   @can('edit_paygrade')
                                        <x-system.modal size="modal-lg" id="editPayGrade{{ $pay_grade->id }}" title="Edit Pay Grade"
                                        form="editPayGradeForm{{ $pay_grade->id }}">
                                        <form id="editPayGradeForm{{ $pay_grade->id }}"
                                            action="{{ route('employee.manage.paygrades.update', $pay_grade) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="name">PayGrade Name</label>
                                                    <input type="text" value="{{ $pay_grade->name }}" class="form-control"
                                                        name="name" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="base_salary">Base Salary</label>
                                                    <input type="number" value="{{ $pay_grade->base_salary }}"
                                                        class="form-control" name="base_salary" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="max_salary">Max Salary</label>
                                                    <input type="number" value="{{ $pay_grade->max_salary }}"
                                                        class="form-control" name="max_salary" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="base_month_count">Base Month Count</label>
                                                    <input type="number" value="{{ $pay_grade->base_month_count }}"
                                                        class="form-control" name="base_month_count" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea class="form-control"
                                                        name="description">{{ $pay_grade->description }}</textarea>
                                                </div>
                                            </div>
                                        </form>
                                    </x-system.modal>
                                   @endcan

                                @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">No pay_grades found.</td>
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
