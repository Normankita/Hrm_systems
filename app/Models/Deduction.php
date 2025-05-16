<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    protected $fillable = [
        'name',
        'amount',
        'employee_id'
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
