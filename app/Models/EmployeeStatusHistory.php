<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeStatusHistory extends Model
{
    protected $fillable = [
        'employee_id',
        'status_id',
        'assigned_by',
        'isActive',
        'effective_date',
        'reason',
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function status()
{
    return $this->belongsTo(Status::class);
}

}
