<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisbursedAllowance extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'company_id',
        'employee_id',
        'status',
        // Add other fields as necessary
    ];
}
