<?php

namespace App\Models;

use App\Http\Utils\Traits\HasEvents;
use App\Http\Utils\Traits\onBootTrait;
use App\Models\Scopes\AuthUserCompanyScope;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class LeaveType extends Model
{
    use HasEvents, onBootTrait;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'description',
        'deducts_from_annual_leave',
        'required_approval',
        'eligibility_criteria',
        'is_compensated',
    ];

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class, 'leave_type_role');
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
