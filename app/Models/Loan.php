<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'employee_id',
        'amount',
        'repayment_months',
        'monthly_deduction',
        'start_date',
        'status' // (ENUM: ongoing, completed)
    ];
}
