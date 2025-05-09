<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    protected $fillable= [
        'employee_id',
        'hours',
        'amount_per_hour',
        'approved_by',
    ];
}
