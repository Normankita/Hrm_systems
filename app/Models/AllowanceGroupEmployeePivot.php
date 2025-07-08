<?php

namespace App\Models;

use App\Http\Utils\Traits\HasEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class AllowanceGroupEmployeePivot extends Model
{
    use HasEvents;
    protected $table = 'allowance_group_employee';

    protected $fillable = [
        'allowance_group_id',
        'employee_id',
        'isActive'
    ];

    public function allowances()
    {
        return $this->belongsToMany(Allowance::class, 'group_category_employee_allowances')
            ->withPivot(['id', 'amount', 'effective_from', 'status'])
            ->withTimestamps();
    }

    public function getGroup()
    {
        return AllowanceGroup::find($this->allowance_group_id);
    }

    public function getGroups(Collection $gr_employee_pivots)
    {
        $gr_allowanceIds = $gr_employee_pivots->pluck('allowance_group_id');
        return AllowanceGroup::whereIn('id', $gr_allowanceIds)->get();
    }


    public function getEmployee()
    {
        return Employee::find($this->employee_id);
    }

    /**
     * Summary of getEmployees
     * @param \Illuminate\Support\Collection<int, \App\Models\AllowanceGroupEmployeePivot> $gr_allowances
     * @return \Illuminate\Database\Eloquent\Collection<int, Employee>
     */
    public static function getEmployees(Collection $gr_employee_pivots): Collection
    {
        $employeeIds = $gr_employee_pivots->pluck('employee_id');
        $employees = Employee::whereIn('id', $employeeIds)->get();
        return $employees;
    }


    public function withEmployee()
    {
        $employee = Employee::find($this->employee_id);
        $this->employee = $employee;
        return $this;
    }

    public static function withEmployees(Collection $gr_employee_pivots): Collection
    {
        $pivotsWithEmployee = $gr_employee_pivots->map(function ($pivot) {
            $employee = Employee::find($pivot->employee_id);
            $pivot->employee = $employee;
            if ($pivot->pivot) {
                $pivot->pivotAllowanceAmount = $pivot->pivot?->amount;
                $frequency = AllowanceFrequency::find($pivot->pivot->allowance_frequency_id);
                $pivot->pivotFrequency = $frequency;
                $pivot->pivotId = $pivot->pivot->id;
            }
            return $pivot;
        });
        return $pivotsWithEmployee;
    }


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function group()
    {
        return $this->belongsTo(AllowanceGroup::class, 'allowance_group_id');
    }

    public function frequencies()
    {
        return $this->belongsToMany(AllowanceFrequency::class, 'group_category_employee_allowances', 'allowance_group_employee_pivot_id', 'allowance_frequency_id')
            ->withPivot(['id', 'amount', 'effective_from', 'status', 'allowance_id']) // optional
            ->withTimestamps();
    }

    public function allowanceGroupAllowancesPivot()
    {
        return $this->belongsToMany(
            AllowanceGroupAllowancePivot::class,
            'group_category_employee_allowances',
            'allowance_group_employee_pivot_id',
            'allowance_group_allowance_pivot_id'
        )
            ->withPivot([
                    'id',
                    'allowance_frequency_id',
                    'amount',
                    'effective_from',
                    'isActive'
                ])
            ->withTimestamps();
    }


        public function activeAllowanceGroupAllowancesPivot()
    {
        return $this->belongsToMany(
            AllowanceGroupAllowancePivot::class,
            'group_category_employee_allowances',
            'allowance_group_employee_pivot_id',
            'allowance_group_allowance_pivot_id'
        )
            ->withPivot([
                    'id',
                    'allowance_frequency_id',
                    'amount',
                    'effective_from',
                    'isActive'
                ])
            ->withTimestamps();
    }

    public static function getRealDetails(int $id)
    {
        return AllowanceGroupEmployeePivot::select('id', 'employee_id', 'allowance_group_id')->with(
            ['employee', 'group']
        )
            ->find($id);
    }
}
