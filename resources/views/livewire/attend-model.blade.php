@php
    $isCheckOutShown = $todayAttendance && !$employeeOut;
    $isCheckinDisabled = !is_null($checkIn);
@endphp

<div>
    @can('check-attendance')
        @if (!$employeeOut)
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#attendanceModal">
                Daily Attendance
            </button>

            <!-- Modal -->
            <div wire:ignore.self class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="attendanceModalLabel">Create Attendance</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <!-- Livewire Form -->
                            <form>
                                <div class="mb-3">
                                    <label for="attendanceDate" class="form-label">Date</label>
                                    <input type="date" id="attendanceDate" class="form-control"
                                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" disabled>
                                    @error('date')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="checkIn" class="form-label">Check In</label>
                                    {{-- <input type="time" id="checkIn" class="form-control" :value="checkIn"
                                        {{ $isCheckinDisabled ? 'disabled' : '' }}>
                                    @error('checkIn')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror --}}
                                    <p>{{ $checkIn }}</p>
                                </div>

                                @if ($isCheckOutShown)
                                    <div class="mb-3">
                                        <label for="checkOut" class="form-label">Check Out</label>
                                        {{-- <input type="time" id="checkOut" class="form-control"
                                        wire:model.defer="checkOut">
                                    @error('checkOut')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div> --}}
                                        <p>{{ $checkOut }}</p>
                                @endif

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea id="notes" class="form-control" rows="3" wire:model.defer="notes"></textarea>
                                    @error('notes')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button wire:click.prevent="saveAttendance" type="button" class="btn btn-primary">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endcan

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

<!-- Include Bootstrap 5 Modal JS Listener -->
<script>
    window.addEventListener('hideAttendanceModal', () => {
        const modalEl = document.getElementById('attendanceModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
    });
</script>
