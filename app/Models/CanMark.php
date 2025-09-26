<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CanMark extends Model
{
    protected $fillable = [
        'employee_id',
        'can_mark',
        'date'
    ];
}
