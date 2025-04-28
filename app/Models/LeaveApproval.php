<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveApproval extends Model
{
        protected $fillable = [
        'employee_id',
        'leave_id',
        'inspector_id',
        'status',
        'comment',
        'inspected_at',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
