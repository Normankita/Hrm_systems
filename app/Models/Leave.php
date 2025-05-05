<?php

namespace App\Models;

use App\Models\Scopes\AuthUserCompanyScope;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'status',
        'reason',
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
}
