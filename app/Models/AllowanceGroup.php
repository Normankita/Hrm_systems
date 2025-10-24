<?php

namespace App\Models;

use App\Http\Utils\Traits\HasDateFilter;
use App\Http\Utils\Traits\HasEvents;
use App\Http\Utils\Traits\onBootTrait;
use App\Models\Scopes\AuthUserCompanyScope;
use Illuminate\Database\Eloquent\Model;

class AllowanceGroup extends Model
{

    use HasDateFilter, HasEvents, onBootTrait;

    public function getAllowancesAttribute()
    {
        return $this->groupEmployees
            ->load('allowances')
            ->flatMap(fn($entry) => $entry->allowances)
            ->unique('id')
            ->values();
    }

    protected $fillable = [
        'name',
        'company_id',
        'created_by',
        'isActive',
        'description',
    ];


    public function employees()
    {
        return $this->belongsToMany(
            Employee::class,
            'allowance_group_employee'
        )->withPivot(['id', 'isActive'])
            ->withTimestamps();
    }

    public function allowance()
    {
        return $this->belongsToMany(
            Allowance::class,
            'allowance_group_allowance',
            'allowance_group_id',
            'allowance_id'
        )
            ->withPivot(['isActive', 'id'])

            ->withTimestamps();
    }

    public function activeEmployees()
    {
        return $this->employees()->wherePivot('isActive', true);
    }

    public function inActiveEmployees()
    {
        return $this->employees()->wherePivot('isActive', false);
    }

    public function groupEmployees()
    {
        return $this->hasMany(AllowanceGroupEmployeePivot::class, 'allowance_group_id');
    }

    public function activeGroups() {
        return $this->where('isActive', true)
            ->get();
    }

    public function assignedAllowanceEntries($allowanceId)
    {
        return $this->groupEmployees
            ->load('allowances')
            ->flatMap(
                fn($entry) =>
                $entry->allowances
                    ->where('id', $allowanceId)
                    ->map(function ($allowance) use ($entry) {
                        return (object) [
                            'employee' => $entry->employee,
                            'allowance' => $allowance,
                            'pivot' => $allowance->pivot
                        ];
                    })
            );
    }


}
