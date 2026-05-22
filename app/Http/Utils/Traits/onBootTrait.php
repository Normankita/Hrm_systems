<?php

namespace App\Http\Utils\Traits;

use App\Models\Company;
use App\Models\Scopes\AuthUserCompanyScope;

trait onBootTrait
{
    /**
     * This method can be used to perform actions when the application boots.
     * It can be used to register services, event listeners, etc.
     */
    protected static function booted()
    {
        // Automatically apply a global scope to all queries
        static::addGlobalScope(new AuthUserCompanyScope);

        // Automatically assign the tenant_id when creating a new record
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

}
