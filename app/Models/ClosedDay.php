<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClosedDay extends Model
{
    protected $fillable = [
        'company_id',
        'closed_date',
        'is_active',
    ];
}
