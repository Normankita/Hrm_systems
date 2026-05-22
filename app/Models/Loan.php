<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'employee_id',
        'amount',
        'total_payable',
        'loan_type',
        'months_to_pay',
        'monthly_deduction',
        'issued_date',
        'status',
        'remarks',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function repayments()
    {
        return $this->hasMany(LoanRepayment::class);
    }

    public function getMonthlyDeductionAttribute()
    {
        return $this->total_payable / $this->months_to_pay;
    }

    public function getRemainingBalanceAttribute()
    {
        $paid = $this->repayments()->sum('amount_paid');
        return $this->total_payable - $paid;
    }
}
