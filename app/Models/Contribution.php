<?php

namespace App\Models;

use App\Models\Scopes\AuthUserCompanyScope;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{

    // protected static function booted()
    // {
    //     // Automatically apply a global scope to all queries
    //     static::addGlobalScope(new AuthUserCompanyScope);

    //     // Automatically assign the tenant_id when creating a new record
    //     static::creating(function ($item) {
    //         if (auth()->check()) {
    //             if (auth()->user()->hasRole('OWNER')) {

    //             } else {
    //                 $company = Company::find(auth()->user()->company_id);
    //                 if ($company) {
    //                     $item->company_id = auth()->user()->company_id;
    //                 }
    //             }
    //         }
    //     });
    // }

    protected $fillable = [
        'name',
        'percent',
        'description',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
