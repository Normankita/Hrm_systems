<?php

namespace App\Models;

use App\Http\Utils\Traits\onBootTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceRecord extends Model
{
    use onBootTrait, HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'employee_id',
        'attendance_session_id',
        'date',
        'status',
        'check_in',
        'check_out',
        'is_from_attendance',
        'remarks',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function attendanceSession()
    {
        return $this->belongsTo(AttendanceSession::class);
    }
}
