<?php

namespace App\Models;

use App\Http\Utils\Traits\HasAllowanceDisbursements;
use App\Http\Utils\Traits\HasEvents;
use Illuminate\Database\Eloquent\Model;

class EmployeeAllowance extends Model
{
    use HasAllowanceDisbursements, HasEvents;

    protected $table = 'employee_allowance';

    protected $fillable = [
        'employee_id',
        'allowance_frequency_id',
        'allowance_id',
        'amount',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }


    public function allowance()
    {
        return $this->belongsTo(Allowance::class);
    }

    public function frequency()
    {
        return $this->belongsTo(AllowanceFrequency::class, 'allowance_frequency_id');
    }

    public function payrolls()
    {
        return $this->belongsToMany(Payroll::class,
         'employee_allowance_payroll');
    }

}
