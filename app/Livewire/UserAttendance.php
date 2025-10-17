<?php

namespace App\Livewire;

use App\Http\Utils\Traits\Livewire\WithSortingAndSearch;
use App\Models\Attendance;
use App\Models\AttendanceRecord;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class UserAttendance extends Component
{

    use WithPagination, WithSortingAndSearch;

    public $attendance;

    public $search;

    public $selecteAttendance;

    public $perPage;

    public $sortField = "created_at";

    public $sortDirection = 'desc';


    public function viewAttendance($id, $employee = null)
    {
        $employee = $employee ?? Auth::user()->employee;
        $this->selecteAttendance = Attendance::find($id);
        if (!$this->selecteAttendance) {
            return;
        }
        $records = AttendanceRecord::where('employee_id', $employee->id)
            ->where(
                'date',
                $this->selecteAttendance->attendance_date
            )
            ->where('is_from_attendance', false)
            ->get();
        $this->selecteAttendance->records = $records;
        return;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }


    #[On('attendanceUpdated')]
    public function refreshList()
    {
        // This will re-run render()
        $this->render();
    }


    public function render()
    {
        $user = Auth::user();
        $employee = $this->employee ?? $user->employee;

        $attendance = $employee->attendances()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('status', 'like', '%' . $this->search . '%')
                        ->orWhere('attendance_date', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.user-attendance', [
            'attendances' => $attendance
        ]);
    }
}


