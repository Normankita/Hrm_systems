<div class="row justify-content-start">
    <div class="col-md-12">
        <div class="form-group">
            <input type="text" class="form-control" wire:model.live="search" placeholder="Search by status or date...">
        </div>
    </div>
    <div class="col-md-12">
        {{-- Nothing in the world is as soft and yielding as water. --}}
        <table class="table">
            <thead class="table-light table-sm">
                <tr>
                    <th>#</th>
                    <th scope="col">Date</th>
                    <th scope="col">In Time</th>
                    <th scope="col">Out Time</th>
                    <th scope="col">Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attendances as $attendance)
                    <tr>
                        <td>{{ ($attendances->currentPage() - 1) * $attendances->perPage() + $loop->iteration }}</td>
                        <td>{{ $attendance->attendance_date }}</td>
                        <td>{{ $attendance->check_in_time }}</td>
                        <td>{{ $attendance->check_out_time }}</td>
                        <td>{{ $attendance->status }}</td>
                        <td>
                            <button wire:click="viewAttendance({{ $attendance->id }})" type="button"
                                data-toggle="modal" data-target="#attendance_details"
                                class="btn btn-sm btn-outline-info py-1">
                                view details
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No attendance found.</td>
                    </tr>
                @endforelse
        </table>

        <div wire:ignore.self class="modal fade" id="attendance_details" tabindex="-1"
            aria-labelledby="attendanceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="attendanceModalLabel">
                            @if ($selecteAttendance)
                                {{ \Carbon\Carbon::parse($selecteAttendance->attendance_date)->format('d M Y') }}
                                Attendance Details
                            @endif
                        </h5>
                    </div>

                    <div class="modal-body">
                        @if (!$selecteAttendance)
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        @endif
                        <div wire:loading.remove wire:target="selecteAttendance">
                            @if ($selecteAttendance)
                                <!-- create a bootstrap loader for refresh waiting -->
                                <div wire:loading class="">
                                    <span>Loading...</span>
                                </div>
                                <table wire:loading.remove class="table table-bordered table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th scope="col">In Time</th>
                                            <th scope="col">Out Time</th>
                                            <th scope="col">notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($selecteAttendance?->records ?? [] as $attendance)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $attendance->check_in }}</td>
                                                <td>{{ $attendance->check_out }}</td>
                                                <td>{{ $attendance->remarks }}</td>
                                            </tr>
                                        @endforeach
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- display the paginator below -->
        {{ $attendances->links() }}
    </div>
</div>

<!-- Include Bootstrap 5 Modal JS Listener -->
<script>
    window.addEventListener('hideAttendanceShowDetailsModal', () => {
        const modalEl = document.getElementById('attendance_details');
        // Initialize or get the Bootstrap modal instance
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.hide();
    });
</script>


<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('attendanceUpdate', () => {
            @this.build();
        });
    });
</script>
