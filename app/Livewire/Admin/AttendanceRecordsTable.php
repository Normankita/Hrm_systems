<?php

namespace App\Livewire\Admin;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use Illuminate\Support\Facades\Log;
use Livewire\Component;


class AttendanceRecordsTable extends Component
{
    use \Livewire\WithPagination;

    public $paginationTheme = 'bootstrap';
    public $search = '';
    public $perPage = 20;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }


    public function render()
    {
        $attendanceRecords = AttendanceRecord::query()
            ->with('employee', 'attendanceSession')
            ->when($this->search, function ($query) {
                $query->orWhere('check_in', 'like', '%' . $this->search . '%')
                    ->orWhere('check_out', 'like', '%' . $this->search . '%')
                    ->orWhereHas('attendanceSession', function ($secQuery) {
                        $secQuery->where('session_type', 'like', '%' . $this->search . '%');
                    });
            }) // ✅ close the when() closure here
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);
        return view('livewire.admin.attendance-records-table', [
            'attendanceRecords' => $attendanceRecords
        ]);
    }

}

