<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAllowance extends Model
{
    protected $table = 'employee_allowance';

    protected $fillable = [
        'employee_id',
        'allowance_id',
        'amount',
        'effective_from',
        'effective_to',
        'frequency',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function allowance()
    {
        return $this->belongsTo(Allowance::class);
    }
}
