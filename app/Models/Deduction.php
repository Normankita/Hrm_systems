<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name',
        'amount',
        'installments',
        'installment_amount',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrolls()
    {
        return $this->belongsToMany(Payroll::class)
                    ->withPivot('amount')
                    ->withTimestamps();
    }
}
