<?php

namespace App\Models;

use App\Http\Utils\Traits\HasEvents;
use App\Models\Scopes\AuthUserCompanyScope;
use Illuminate\Database\Eloquent\Model;

class PayGrade extends Model
{
    use HasEvents;

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
        'base_salary',
        'max_salary',
        'base_month_count',
        'description',
        'company_id',
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class)
        ->withPivot(['id', 'status', 'assigned_by', 'effective_from', 'base_salary_override'])->withTimestamps();
    }
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

}
