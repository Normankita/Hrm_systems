@extends('layouts.system')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('admin.trainings.index') }}" class="btn btn-light btn-sm">
                <i class="mdi mdi-arrow-left"></i> Back to Trainings
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Training details --}}
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $training->name }}</h5>
                    <x-system.modal-button class="btn btn-sm btn-primary" id="editTrainingShow" text="Edit" />
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" width="140">Type</td>
                            <td><span class="badge bg-info text-dark">{{ $training->type }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td><span class="badge bg-primary">{{ $training->status }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Departments</td>
                            <td>
                                @if ($training->departments->isNotEmpty())
                                    @foreach ($training->departments as $dept)
                                        <span class="badge bg-light text-dark border me-1">{{ $dept->name }}</span>
                                    @endforeach
                                @else
                                    All Departments
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Schedule</td>
                            <td>
                                {{ $training->start_date->format('d M Y') }}
                                @if ($training->end_date)
                                    – {{ $training->end_date->format('d M Y') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Duration</td>
                            <td>{{ $training->duration ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Location</td>
                            <td>{{ $training->location ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Instructors</td>
                            <td>
                                @if ($training->instructors->isNotEmpty())
                                    @foreach ($training->instructors as $instructor)
                                        <span class="badge bg-primary me-1 mb-1">{{ $instructor->name }}</span>
                                    @endforeach
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Registered by</td>
                            <td>{{ $training->createdBy?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Enrolled</td>
                            <td><strong>{{ $training->participants_count }}</strong> employee(s)</td>
                        </tr>
                    </table>
                    @if ($training->description)
                        <hr>
                        <p class="small text-muted mb-1">Description</p>
                        <p class="mb-0">{{ $training->description }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Enrollment --}}
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="mdi mdi-account-multiple-plus me-1"></i> Enroll Participants</h6>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        {{-- Per department --}}
                        <div class="col-md-6 border-end">
                            <h6 class="text-muted small text-uppercase mb-3">By Department</h6>
                            <form method="POST" action="{{ route('admin.trainings.enroll.department', $training) }}">
                                @csrf
                                <div class="mb-3">
                                    <select name="department_id" class="form-control training-select2-single" required>
                                        <option value="">Select department...</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                                    Enroll All in Department
                                </button>
                            </form>
                            <small class="text-muted d-block mt-2">Registers every employee in the selected department.</small>
                        </div>

                        {{-- Individual employees --}}
                        <div class="col-md-6">
                            <h6 class="text-muted small text-uppercase mb-3">Individual Employees</h6>
                            <form method="POST" action="{{ route('admin.trainings.enroll.employees', $training) }}">
                                @csrf
                                <div class="mb-3">
                                    <select name="employee_ids[]" class="form-control training-select2-employees" multiple required>
                                        @foreach ($employees as $employee)
                                            @if (!in_array($employee->id, $enrolledEmployeeIds))
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->full_name }}
                                                    @if ($employee->department)
                                                        ({{ $employee->department->name }})
                                                    @endif
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    Enroll Selected
                                </button>
                            </form>
                            <small class="text-muted d-block mt-2">Hold Ctrl/Cmd to select multiple.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Participants table --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Enrolled Participants ({{ $participants->count() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Enrolled</th>
                            <th>Completed</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($participants as $key => $participant)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $participant->employee->full_name }}</td>
                                <td>{{ $participant->employee->department?->name ?? '—' }}</td>
                                <td>
                                    <form method="POST"
                                        action="{{ route('admin.trainings.participants.update', [$training, $participant]) }}"
                                        class="d-flex gap-1 align-items-center">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-control form-control-sm"
                                            onchange="this.form.submit()">
                                            @foreach (\App\Enums\TrainingParticipantStatusEnum::cases() as $status)
                                                <option value="{{ $status->value }}"
                                                    @selected($participant->status === $status->value)>
                                                    {{ $status->value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td class="small">{{ $participant->enrolled_at?->format('d M Y') }}</td>
                                <td class="small">{{ $participant->completed_at?->format('d M Y') ?? '—' }}</td>
                                <td class="text-center">
                                    <form method="POST"
                                        action="{{ route('admin.trainings.participants.destroy', [$training, $participant]) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('Remove this participant?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove">
                                            <i class="mdi mdi-account-remove"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No participants enrolled yet. Use department or individual enrollment above.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-system.modal size="modal-lg" id="editTrainingShow" title="Edit Training" form="editTrainingShowForm">
        <form id="editTrainingShowForm" method="POST" action="{{ route('admin.trainings.update', $training) }}">
            @csrf
            @method('PUT')
            @include('admin.trainings._form-fields', ['training' => $training])
        </form>
    </x-system.modal>
@endsection

@section('scripts')
    @include('admin.trainings._select2-scripts')
    <script>
        $(document).ready(function() {
            $('.training-select2-single').select2({
                placeholder: 'Select department to enroll',
                allowClear: true,
                width: '100%'
            });
            $('.training-select2-employees').select2({
                placeholder: 'Search and select employees',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endsection
