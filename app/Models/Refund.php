<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'refund_amount',
        'loan_id',
        'refund_date',
        'payroll_id',
        'employee_id'
    ];
}
