<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayGrade extends Model
{
    protected $fillable = [
        'name',
        'base_salary',
        'max_salary',
        'base_month_count',
        'description',
    ];

    public function employees(){
        return $this->hasMany(Employee::class);
    }
}
