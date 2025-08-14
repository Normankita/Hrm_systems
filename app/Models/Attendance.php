<?php

namespace App\Models;

use App\Http\Utils\Traits\onBootTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use onBootTrait, HasFactory;

    protected $fillable = [
        'company_id',
        'employee_id',
        'check_in_time',
        'check_out_time',
        'attendance_date',
        'status',
        'remarks',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function scopeDated($query, $date)
    {
        return $query->whereDate('attendance_date', $date);
    }

    public function scopeLateComers($query)
    {
        return $query->where('status', 'late');
    }
}
