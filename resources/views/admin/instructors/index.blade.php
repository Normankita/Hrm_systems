@extends('layouts.system')

@section('title')
    Instructors
@endsection

@section('styles')
    <style>

    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-1">Instructors</h4>
                            <p class="text-muted mb-0 small">Register trainers, then assign them when creating trainings</p>
                        </div>
                        <x-system.modal-button class="btn btn-primary" id="createInstructor" text="Register Instructor" />
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Specialization</th>
                                    <th>Contact</th>
                                    <th>Linked Employee</th>
                                    <th>Trainings</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($instructors as $key => $instructor)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="fw-medium">{{ $instructor->name }}</td>
                                        <td>{{ $instructor->specialization ?? '—' }}</td>
                                        <td class="small">
                                            @if ($instructor->email)
                                                <div>{{ $instructor->email }}</div>
                                            @endif
                                            @if ($instructor->phone)
                                                <div>{{ $instructor->phone }}</div>
                                            @endif
                                            @if (!$instructor->email && !$instructor->phone)
                                                —
                                            @endif
                                        </td>
                                        <td>{{ $instructor->employee?->full_name ?? '—' }}</td>
                                        <td>{{ $instructor->trainings_count }}</td>
                                        <td>
                                            @if ($instructor->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <x-system.modal-button class="btn btn-sm btn-primary"
                                                id="editInstructor-{{ $instructor->id }}" text="Edit" />
                                            <form action="{{ route('admin.instructors.destroy', $instructor) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Delete this instructor?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            No instructors registered yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-system.modal size="modal-lg" id="createInstructor" title="Register Instructor" form="createInstructorForm">
        <form id="createInstructorForm" method="POST" action="{{ route('admin.instructors.store') }}">
            @csrf
            @include('admin.instructors._form-fields', ['instructor' => null])
        </form>
    </x-system.modal>

    @foreach ($instructors as $instructor)
        <x-system.modal size="modal-lg" id="editInstructor-{{ $instructor->id }}" title="Edit Instructor"
            form="editInstructorForm-{{ $instructor->id }}">
            <form id="editInstructorForm-{{ $instructor->id }}" method="POST"
                action="{{ route('admin.instructors.update', $instructor) }}">
                @csrf
                @method('PUT')
                @include('admin.instructors._form-fields', ['instructor' => $instructor])
            </form>
        </x-system.modal>
    @endforeach
@endsection

@section('scripts')
    @include('admin.trainings._select2-scripts')
    <script>
        $(document).ready(function() {
            $('.instructor-select2-employee').select2({
                placeholder: 'Link to employee (optional)',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endsection
