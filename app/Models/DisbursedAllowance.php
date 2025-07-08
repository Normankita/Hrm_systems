<?php

namespace App\Models;

use App\Enums\AllowanceGroups;
use App\Models\Scopes\AuthUserCompanyScope;
use Illuminate\Database\Eloquent\Model;

class DisbursedAllowance extends Model
{

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

    // create a enum that i can user for type ['individual', 'group', '']

    public function __construct()
    {

    }

    protected $fillable = [
        'type',
        'amount',
        'company_id',
        'employee_id',
        'status',
        'disbursable_id',
        'disbursable_type',
    ];

    public function disbursable()
    {
        return $this->morphTo();
    }


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public static function getIndividialDisbursements()
    {
        return self::where('type', AllowanceGroups::INDIVIDUAL)->get();
    }

    public static function getGroupDisbursements()
    {
        return self::where('type', AllowanceGroups::GROUP)->get();
    }

    public static function getCategorizedDisbursements()
    {
        return self::where('type', AllowanceGroups::CATEGORY)->get();
    }


}
