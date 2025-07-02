<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupCategoryEmployeeAllowance extends Model
{
    protected $fillable=[
      'allowance_group_employee_id',
      'allowance_id',
      'amount',
      'allowance_frequency_id',
      'effective_from',
      'status' 
    ];

        public function allowance_group_employee()
    {
        return $this->belongsTo(AllowanceGroupEmployeePivot::class);
    }


    public function allowance()
    {
        return $this->belongsTo(Allowance::class);
    }

    public function frequency()
    {
        return $this->belongsTo(AllowanceFrequency::class, 'allowance_frequency_id');
    }

}
