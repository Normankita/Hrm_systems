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
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No attendance found.</td>
                    </tr>
                @endforelse
        </table>
        <!-- display the paginator below -->
        {{ $attendances->links() }}
    </div>
</div>
