<?php

namespace App\Models;

use App\Http\Utils\Traits\HasEvents;
use Illuminate\Database\Eloquent\Model;

class LeaveApproval extends Model
{
    use HasEvents;
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

    public function leave() {
        return $this->belongsTo(Leave::class);
    }
}
