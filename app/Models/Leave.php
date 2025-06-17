<?php

namespace App\Models;

use App\Http\Utils\Traits\HasDateFilter;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasDateFilter;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'status',
        'reason',
        'comment',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function attachments()
    {
        return $this->morphMany(
            Attachment::class, 'attachmentable');
    }

    public function leave_appraval()
    {
        return $this->hasOne(LeaveApproval::class);
    }
}
