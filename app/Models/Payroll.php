<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id',
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
        'payslip_path'
    ];
}