<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupCategoryEmployeeAllowance extends Model
{
    protected $fillable = [
        'allowance_group_employee_pivot_id',
        'allowance_group_allowance_pivot_id',
        'amount',
        'allowance_frequency_id',
        'effective_from',
        'status'
    ];

    public function group_employee_pivot()
    {
        return $this->belongsTo(AllowanceGroupEmployeePivot::class,
            'allowance_group_employee_pivot_id',
            'id');
    }

    public function group_allowance_pivot() {
        return $this->belongsTo(AllowanceGroupAllowancePivot::class,
            'allowance_group_allowance_pivot_id',
            'id');
    }

    public function frequency()
    {
        return $this->belongsTo(AllowanceFrequency::class, 'allowance_frequency_id');
    }

}
