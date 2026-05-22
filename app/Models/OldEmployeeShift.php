<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OldEmployeeShift extends Model
{
    protected $table = 'old_employee_shifts';

    protected $fillable = [
        'company_id',
        'employee_id',
        'attendance_session_id',
        'changed_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
