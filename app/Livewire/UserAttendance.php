<?php

namespace App\Livewire;

use App\Http\Utils\Traits\Livewire\WithSortingAndSearch;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UserAttendance extends Component
{

    use WithPagination, WithSortingAndSearch;

    public $attendance;

    public $search;

    public $perPage;

    public $sortField = "created_at";

    public $sortDirection = 'desc';


    public function updatingSearch()
    {
        $this->resetPage();
    }




public function render()
{
    $user = Auth::user();
    $employee = Employee::where('user_id', $user->id)->first();

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
