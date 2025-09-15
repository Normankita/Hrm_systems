@extends('layouts.system') <!-- Your main layout -->

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Manage Sessions</h1>
        <!-- Search and controls -->
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="mb-3">
                        <x-system.modal-button id="add-session" text="Add New Session">
                        </x-system.modal-button>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table dt-table table-hover table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Session Name</th>
                                        <th scope="col">Start Date</th>
                                        <th scope="col">End Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Example row -->
                                    @foreach ($sessions as $session)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $session->session_type }}</td>
                                            <td>{{ $session->start_time }}</td>
                                            <td>{{ $session->end_time }}</td>
                                            <td><span class="badge {{ $session->is_active ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $session->is_active ? 'Active' : 'Inactive' }}
                                                </span></td>
                                            <td class="text-center">
                                                <x-system.modal-button class="btn btn-sm btn-primary" :id="'session-' . $session->id"
                                                    text="Edit" />
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- Add more rows dynamically here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @foreach ($sessions as $session)
            <x-system.modal :id="'session-' . $session->id" title="Edit Session">
                <div>
                    <form action="{{ route('admin.attendances.sessions.update', $session->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="type">Session Name</label>
                            <input type="text" name="type" id="type" value="{{ $session->session_type }}"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="start">Start Date</label>
                            <input type="time" name="start" id="start" value="{{ $session->start_time }}"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="end">End Date</label>
                            <input type="time" name="end" id="end" value="{{ $session->end_time }}"
                                class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Save Changes
                        </button>
                    </form>
                </div>
            </x-system.modal>
        @endforeach

        <x-system.modal id="add-session" title="Add Session">
            <div>
                <form action="{{ route('admin.attendances.sessions.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="type">Session Name</label>
                        <input type="text" name="type" id="type" value="{{ old('session_type') }}"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="start">Start Date</label>
                        <input type="time" name="start" id="start" value="{{ old('start') }}"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="end">End Date</label>
                        <input type="time" name="end" id="end" value="{{ old('end') }}"
                            class="form-control">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">
                        SUBMIT
                    </button>
                </form>
            </div>
        </x-system.modal>
    </div>
@endsection
