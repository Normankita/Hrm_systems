@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-1">Training Programs</h4>
                            <p class="text-muted mb-0 small">Register and manage employee trainings</p>
                        </div>
                        <x-system.modal-button class="btn btn-primary" id="createTraining" text="Register Training" />
                    </div>

                    <form method="GET" class="row g-2 mb-4">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search name or instructor..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="type" class="form-control">
                                <option value="">All Types</option>
                                @foreach (\App\Enums\TrainingTypeEnum::cases() as $type)
                                    <option value="{{ $type->value }}" @selected(request('type') === $type->value)>
                                        {{ $type->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">All Statuses</option>
                                @foreach (\App\Enums\TrainingStatusEnum::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>
                                        {{ $status->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-grid">
                            <button type="submit" class="btn btn-outline-primary">Filter</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Department</th>
                                    <th>Schedule</th>
                                    <th>Instructor</th>
                                    <th>Enrolled</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($trainings as $key => $training)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <a href="{{ route('admin.trainings.show', $training) }}" class="fw-medium">
                                                {{ $training->name }}
                                            </a>
                                        </td>
                                        <td><span class="badge bg-info text-dark">{{ $training->type }}</span></td>
                                        <td>
                                            @php
                                                $statusClass = match ($training->status) {
                                                    'Scheduled' => 'bg-primary',
                                                    'Ongoing' => 'bg-warning text-dark',
                                                    'Completed' => 'bg-success',
                                                    'Cancelled' => 'bg-secondary',
                                                    default => 'bg-light text-dark',
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ $training->status }}</span>
                                        </td>
                                        <td>
                                            @if ($training->departments->isNotEmpty())
                                                @foreach ($training->departments as $dept)
                                                    <span class="badge bg-light text-dark border me-1 mb-1">{{ $dept->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">All</span>
                                            @endif
                                        </td>
                                        <td class="small">
                                            {{ \Carbon\Carbon::parse($training->start_date)->format('d M Y') }}
                                            @if ($training->end_date)
                                                – {{ \Carbon\Carbon::parse($training->end_date)->format('d M Y') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($training->instructors->isNotEmpty())
                                                @foreach ($training->instructors as $instructor)
                                                    <span class="badge bg-primary me-1 mb-1">{{ $instructor->name }}</span>
                                                @endforeach
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>{{ $training->participants_count }}</td>
                                        <td class="text-center text-nowrap">
                                            <a href="{{ route('admin.trainings.show', $training) }}"
                                                class="btn btn-sm btn-outline-primary" title="View & enroll">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <x-system.modal-button class="btn btn-sm btn-primary"
                                                id="editTraining-{{ $training->id }}" text="Edit" />
                                            <form action="{{ route('admin.trainings.destroy', $training) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Delete this training and all enrollments?');">
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
                                        <td colspan="9" class="text-center text-muted py-4">
                                            No trainings registered yet.
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

    {{-- Create Training Modal --}}
    <x-system.modal size="modal-lg" id="createTraining" title="Register Training" form="createTrainingForm">
        <form id="createTrainingForm" method="POST" action="{{ route('admin.trainings.store') }}">
            @csrf
            @include('admin.trainings._form-fields', ['training' => null])
        </form>
    </x-system.modal>

    @foreach ($trainings as $training)
        <x-system.modal size="modal-lg" id="editTraining-{{ $training->id }}" title="Edit Training"
            form="editTrainingForm-{{ $training->id }}">
            <form id="editTrainingForm-{{ $training->id }}" method="POST"
                action="{{ route('admin.trainings.update', $training) }}">
                @csrf
                @method('PUT')
                @include('admin.trainings._form-fields', ['training' => $training])
            </form>
        </x-system.modal>
    @endforeach
@endsection

@section('scripts')
    @include('admin.trainings._select2-scripts')
@endsection
