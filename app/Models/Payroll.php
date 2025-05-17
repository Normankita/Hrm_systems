<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id',
        'pay_grade_id',
        'payroll_date',
        'basic_salary',
        'gross_salary',
        'net_salary',
        'paye',
        'nssf',
        'psssf',
        'sdl',
        'wcf',
        'allowances',
        'deductions',
        'payslip_path',
        'period',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];
    protected $casts = [
        'approved_at' => 'datetime',
        'payroll_date' => 'date',
    ];
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function pay_grade()
    {
        return $this->belongsTo(PayGrade::class);
    }
    public function deductions()
    {
        return $this->belongsToMany(Deduction::class)
            ->withPivot('amount')
            ->withTimestamps();
    }

}
