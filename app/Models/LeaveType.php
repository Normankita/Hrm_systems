<?php

namespace App\Models;

use App\Models\Scopes\AuthUserCompanyScope;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class LeaveType extends Model
{
    protected static function booted()
    {
        // Automatically apply a global scope to all queries
        static::addGlobalScope(new AuthUserCompanyScope);

        // Automatically assign the tenant_id when creating a new record
        static::creating(function ($item) {
            if (auth()->check()) {
                if (auth()->user()->hasRole('OWNER')) {

                }else {
                    $company = Company::find(auth()->user()->company_id);
                    if( $company) {
                        $item->company_id = auth()->user()->company_id;
                    }
                }
            }
        });
    }


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
