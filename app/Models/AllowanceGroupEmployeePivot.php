<?php

namespace App\Models;

use App\Http\Utils\Traits\HasEvents;
use Illuminate\Database\Eloquent\Model;

class AllowanceGroupEmployeePivot extends Model
{
    use HasEvents;
    protected $table = 'allowance_group_employee';

    protected $fillable = [
        'eventable_id',
        'eventable_type',
        'allowance_group_id',
        'employee_id',
        'amount',
        'isActive'
    ];
}
