<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayGrade extends Model
{
    protected $fillable = [
        'name',
        'base_salary',
        'max_salary',
        'base_month_count',
        'description',
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class)->withPivot(['status', 'assigned_by', 'effective_from', 'base_salary_override'])->withTimestamps();
    }
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

}
