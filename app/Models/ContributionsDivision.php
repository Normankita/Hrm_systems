<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContributionsDivision extends Model
{
    protected $table = 'contributions_divisions';
    protected $fillable = [
        'company_nssf',
        'company_psssf',
        'company_paye',
        'company_sdl',
        'company_wcf',
        'employee_nssf',
        'employee_psssf',
        'employee_paye',
        'employee_sdl',
        'employee_wcf',
        'company_id',
        'payroll_id'
    ];

    // Relation to Contribution
    public function payroll()
    {
        return $this->belongsTo(Payroll::class,
             'payroll_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

}
