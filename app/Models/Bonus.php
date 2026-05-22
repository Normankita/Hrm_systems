<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $fillable=[
        'employee_id',
        'amount',
        'reason',
        'approved_by',
    ];
}
