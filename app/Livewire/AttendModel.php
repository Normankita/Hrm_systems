<?php

namespace App\Livewire;

use App\Http\Utils\Traits\AttendanceTrait;
use App\Models\Attendance;
use App\Models\AttendanceRecord;
use App\Models\Employee;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AttendModel extends Component
{
    public $date;
    public $checkIn;
    public $checkOut;
    public $notes;
    public $status;

    public $formType;

    public $employeeOut = false;

    public $todayAttendance;

    private $attendanceDate;

    protected $rules = [
        'date' => 'required|date',
        'checkIn' => 'required',
        'checkOut' => 'nullable',
        'notes' => 'nullable|string|max:500',
    ];

    private function isUpdatingAttendance()
    {
        $checkin = Carbon::now()->format('H:i:s');
        $checkout = Carbon::now()->format('H:i:s');
        $this->attendanceDate = Carbon::now()->format('Y-m-d');
        // Fetching the today attendance for the current user
        $user = auth()->user();
        $employee = Employee::find($user->employee->id);
        $this->date = $this->attendanceDate;
        $this->todayAttendance = $employee->getTodayAttendance();
        if (!$this->todayAttendance) {
            $status = AttendanceTrait::isLate($employee->id) ?
                'late' : 'present';
            $this->checkIn = $checkin;
            $this->status = $status;
        } else {
            $this->checkIn = $this->todayAttendance->check_in_time;
        }
        $this->checkOut = $checkout;
        // also save to attendance records
        return (boolean) $this->todayAttendance;
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
        $this->todayAttendance = $employee->getTodayAttendance();
        if (!$this->todayAttendance) {
            $status = AttendanceTrait::isLate($employee->id) ?
                'late' : 'present';
            $this->status = $status;
        }
        if (!self::isUpdatingAttendance()) {
            $data = [
                'status' => $this->status,
                'attendance_date' => $this->attendanceDate,
                'check_in_time' => $this->checkIn,
                'remarks' => $this->notes,
            ];
        } else {
            $data = [
                'check_out_time' => $this->checkOut,
            ];
        }
        try {
            DB::transaction(function () use ($employee, $data) {
                Attendance::updateOrCreate([
                    'attendance_date' => $this->attendanceDate,
                    'employee_id' => $employee->id
                ], $data);
                $this->saveRecord();
            });
            if ($this->todayAttendance) {
                $this->employeeOut = true;
            }

            session()->flash('success', 'Attendance recorded successfully!');
            // Reset fields
            $this->reset(['message', 'checkIn', 'checkOut', 'notes', 'status']);
            // Hide modal
            $this->dispatch('attendanceUpdated');
        } catch (Throwable $throwable) {
            Log::info('Error creating attendance: ' . $throwable->getMessage());
            DB::rollBack();
            session()->flash('message', 'Attendance recorded successfully!');
        } finally {
            $this->dispatch('hideAttendanceModal');
        }
    }

    private function saveRecord()
    {
        $data = [
            'attendance_session_id' => Auth::user()->employee->attendance_session_id,
            'employee_id' => Auth::user()->employee->id,
            'remarks' => $this->notes,
        ];
        if ($this->todayAttendance) {
            $moreData = [
                'date' => $this->todayAttendance->attendance_date,
                'status' => $this->todayAttendance->status,
                'check_in' => $this->todayAttendance->check_in_time,
                'check_out' => $this->checkOut,
            ];
        } else {
            $moreData = [
                'date' => $this->attendanceDate,
                'status' => $this->status,
                'check_in' => $this->checkIn,
                'is_from_attendance' => true
            ];
        }
        $data = array_merge($data, $moreData);
        AttendanceRecord::create($data);
    }
}
