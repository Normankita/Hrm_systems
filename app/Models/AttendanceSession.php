<?php

namespace App\Models;

use App\Http\Utils\Traits\onBootTrait;
use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    use onBootTrait;
    
    protected $fillable = [
        'company_id',
        'session_type',
        'start_time',
        'end_time',
        'is_active',
    ];

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }
}
