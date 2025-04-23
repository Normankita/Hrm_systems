<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'employee_id',
        'leave_typpe',
        'start_date',
        'end_date',
        'status',
        'reason',
    ];
}
