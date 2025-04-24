<?php

namespace App\Models;

use App\Models\Scopes\AuthUserCompanyScope;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
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
        'user_id',
        'company_id',
        'department_id',
        'full_name',
        'gender',
        'date_of_birth',
        'phone_number',
        'email',
        'national_id',
        'marital_status',
        'residential_address',
        'tin_number',
        'employee_type',
        'date_of_hire',
        'date_of_termination',
        'salary'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function designation() {
        return $this->belongsTo(DesignationRoleMapping::class);
    }

    public function contract() {
        return $this->hasOne(EmployeeContract::class);
    }

    public function documents() {
        return $this->hasMany(EmployeeDocument::class);
    }

}
