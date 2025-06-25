<?php

namespace App\Models;

use App\Http\Utils\Traits\HasDateFilter;
use App\Http\Utils\Traits\HasEvents;
use App\Models\Scopes\AuthUserCompanyScope;
use Illuminate\Database\Eloquent\Model;

class AllowanceGroup extends Model
{

    use HasDateFilter, HasEvents;

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

    protected $fillable = [
        'name',
        'company_id',
        'created_by',
        'isActive',
        'description',
    ];

    public function employees()
    {
        return $this->belongsToMany(
            Employee::class,
            'allowance_group_employee'
        )->withPivot(['isActive'])
            ->withTimestamps();
    }

    public function allowance()
    {
        return $this->belongsTo(Allowance::class);
    }

    public function activeEmployees()
    {
        return $this->employees()->wherePivot('isActive', true);
    }

    public function inActiveEmployees()
    {
        return $this->employees()->wherePivot('isActive', false);
    }
}
