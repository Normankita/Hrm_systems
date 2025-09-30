<?php

namespace App\Livewire;

use App\Http\Utils\Traits\AttendanceTrait;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AttendModel extends Component
{
    public $date;
    public $checkIn;
    public $checkOut;
    public $notes;
    public $status;

    public $formType;

    protected $rules = [
        'date' => 'required|date',
        'checkIn' => 'required',
        'checkOut' => 'nullable',
        'notes' => 'nullable|string|max:500',
    ];


    private function isUpdatingAttendance()
    {
        $checkout = Carbon::now()->format('H:i:s');
        $checkin = Carbon::now()->format('H:i:s');
        $attendDate = Carbon::now()->format('Y-m-d');
        // Fetching the today attendance for the current user
        $user = auth()->user();
        $employee = Employee::find($user->employee->id);
        $this->date = $attendDate;
        $todayAttendance = $employee->getTodayAttendance();
        if ($todayAttendance) {
            $this->checkOut = $checkout;
        } else {
            $status = AttendanceTrait::isLate($employee->id) ? 'late' : 'present';
            $this->checkIn = $checkin;
            $this->status = $status;
        }
        // also save to attendance records

        return (boolean) $employee;
    }

    public function render()
    {
        self::isUpdatingAttendance();
        return view('livewire.attend-model');
    }

    public function saveAttendance()
    {
        $this->validate();

        // Example save logic:
        $employee = Auth::user()->employee;
        $attendanceDate = Carbon::now()->format('Y-m-d');
        $currentTime = now()->format('H:i:s');
        dd(self::isUpdatingAttendance());
        if (!self::isUpdatingAttendance()) {
            $data = [
                'status' => $this->status,
                'attendance_date' => $attendanceDate,
                'check_in' => $this->checkIn,
                'remarks' => $this->notes,
            ];
        }
        $data = [
            'check_out' => $this->checkOut,
        ];
        $employee->attendances()->create($data);

        // Flash message
        session()->flash('message', 'Attendance recorded successfully!');

        // Reset fields
        $this->reset(['date', 'checkIn', 'checkOut', 'notes', 'status']);

        // Hide modal
        $this->dispatch('hideAttendanceModal');
        $this->dispatch('attendanceUpdated');
    }
}
