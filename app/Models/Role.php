<?php

namespace App\Models;

use App\Models\Scopes\AuthUserCompanyScope;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{

        protected static function booted()
    {
        // Automatically apply a global scope to all queries
        static::addGlobalScope(new AuthUserCompanyScope);

        // Automatically assign the company_id when creating a new record
        static::creating(function ($item) {
            if (auth()->check()) {
                if (auth()->user()->hasRole('OWNER')) {

                } else {
                    $company = Company::find(auth()->user()->company_id);
                    if ($company) {
                        $item->company_id = auth()->user()->company_id;
                    }
                }
            }
        });
    }

    public function leaveTypes()
    {
        return $this->belongsToMany(LeaveType::class, 'leave_type_role');
    }
}
