<div>
    <div class="mb-3 d-flex justify-content-between">
        <input type="text" wire:model.debounce.500ms="search" placeholder="Search..."
               class="form-control w-25" />

        <select wire:model="perPage" class="form-control w-25">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="25">25</option>
        </select>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Date</th>
                <th>Employee</th>
                <th>Status</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $record)
                <tr>
                    <td>{{ $record->date }}</td>
                    <td>{{ $record->employee->name ?? 'N/A' }}</td>
                    <td>{{ $record->status }}</td>
                    <td>{{ $record->check_in }}</td>
                    <td>{{ $record->check_out }}</td>
                    <td>{{ $record->remarks }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $records->links() }}
    </div>
</div>
