<?php

namespace App\Models;

use App\Http\Utils\Traits\HasEvents;
use Illuminate\Database\Eloquent\Model;

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
            ->withPivot(['amount', 'effective_from', 'status'])
            ->withTimestamps();
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
            ->withPivot(['amount', 'effective_from', 'status', 'allowance_id']) // optional
            ->withTimestamps();
    }
}
