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


    public function updatingSearch()
    {
        $this->resetPage();
    }




    public function render()
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)
            ->first();
        $attendance = $employee->attendances()->paginate(10);
        return view('livewire.user-attendance')
        ->with('attendances', $attendance);
    }
}
