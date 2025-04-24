<?php

namespace App\Models\Scopes;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class AuthUserCompanyScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model)
    {
        if (auth()->check()) {
            // Add table name to avoid ambiguity eg. when using joins
            if (Auth::user()->hasRole('OWNER')) {
            } else {
                $company = Company::find(auth()->user()->company_id);
                if ($company) {
                    $builder->where($model->getTable() . '.company_id', auth()->user()->company_id);
                }
            }
        }
    }
}
