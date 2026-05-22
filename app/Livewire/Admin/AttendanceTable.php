<?php

namespace App\Livewire\Admin;

use App\Http\Utils\Traits\Livewire\WithSortingAndSearch;
use App\Models\Attendance;
use Livewire\Attributes\On;
use Livewire\Component;

class AttendanceTable extends Component
{
    use WithSortingAndSearch;

    public string $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $attendances = Attendance::with('employee')
            ->where(function ($query) {
                $query->whereHas('employee', function ($q) {
                    $q->where('full_name', 'like', '%' . $this->search . '%');
                })
                    ->orWhere('status', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.attendance-table', [
            'attendances' => $attendances
        ]);
    }

}
