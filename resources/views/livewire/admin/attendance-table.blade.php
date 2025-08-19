<div>
    <input type="text" wire:model.live="search" placeholder="Search..." class="form-control mb-2">
    <!-- Loader row -->
    <div wire:loading wire:target="search,sortBy" class="text-center py-3">
        <span class="spinner-border text-primary" role="status"></span>
        Loading...
    </div>

    <div wire:loading.remove wire:target="search,sortBy">
        <table class="table td-table table-hover table-bordered align-middle">
            <thead>
                <tr>
                    <th wire:click="sortBy('id')">ID</th>
                    <th wire:click="sortBy('employee_id')">Employee</th>
                    <th wire:click="sortBy('status')">Status</th>
                    <th wire:click="sortBy('created_at')">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->id }}</td>
                        <td>{{ $attendance->employee->full_name }}</td>
                        <td>{{ $attendance->status }}</td>
                        <td>{{ $attendance->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $attendances->links() }}
</div>
