<?php

namespace App\Models;

use App\Http\Utils\Traits\HasDateFilter;
use App\Http\Utils\Traits\HasEvents;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Payroll extends Model
{
    use HasDateFilter, HasEvents;
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
            ->withPivot('total_amount')
            ->withTimestamps();
    }

    public function employeeAllowances()
    {
        return $this->belongsToMany(EmployeeAllowance::class, 'employee_allowance_payroll')->withPivot('amount');
    }



}
