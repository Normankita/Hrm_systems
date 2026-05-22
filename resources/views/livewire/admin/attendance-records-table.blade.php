<div class="p-3">
    <div class="mb-3 d-flex justify-content-between">
        <input type="text" wire:model.live="search" placeholder="Search..." class="form-control w-25" />
        <input type="date" wire:model.live="dateFilter" placeholder="Search..." class="form-control float-left w-25" />

        <div wire:loading wire:target="search" class="text-center py-3">
            <span class="spinner-border text-primary" role="status"></span>
            Loading...
        </div>


        <select wire:model="perPage" class="form-control w-25">
            <option>Pe Page</option>
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="25">25</option>
        </select>
    </div>

    <div wire:loading.remove wire:target="search,perPage">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Session Type</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendanceRecords as $index => $record)
                    <tr>
                        <td>{{ $attendanceRecords->firstItem() + $index }}</td>
                        <td>{{ $record->employee->full_name }}</td>
                        <td>{{ $record->attendanceSession?->session_type }}</td>
                        <td>{{ \Carbon\Carbon::parse($record->date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($record->start_time)->format('h:i A') }}</td>
                        <td>{{ \Carbon\Carbon::parse($record->end_time)->format('h:i A') }}</td>
                        <td>
                            @if ($record->is_from_attendance)
                                --
                                @continue
                            @endif
                            <button class="btn btn-sm btn-info">
                                edit
                            </button>
                            <button class="btn btn-sm btn-danger">
                                <span class="p-0 m-0">delete</span>
                            </button>
                        </td>
                    </tr>
                @endforeach

                {{-- End Example Row --}}
            </tbody>
        </table>

        <div class="mt-3">
            {{ $attendanceRecords->links() }}
        </div>
    </div>
</div>
