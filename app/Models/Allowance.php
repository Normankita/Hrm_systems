<?php
namespace App\Models;

use App\Http\Utils\Traits\HasEvents;
use App\Models\Scopes\AuthUserCompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    use HasFactory, HasEvents;

    protected static function booted()
    {
        // Automatically apply a global scope to all queries
        static::addGlobalScope(new AuthUserCompanyScope);

        // Automatically assign the tenant_id when creating a new record
        static::creating(function ($item) {
            if (auth()->check()) {
                if (auth()->user()->hasRole('OWNER')) {
                    // do anything you want for the owner
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
        'company_id',
        'name',
        'description',
        'is_taxable',
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_allowance')
            ->withPivot(['id', 'amount', 'effective_from', 'status'])
            ->withTimestamps();
    }

    public function allowance_groups()
    {
        return $this->belongsToMany(
            AllowanceGroup::class,
            'allowance_group_allowance',
            'allowance_id',
            'allowance_group_id'
        )->withPivot(['id', 'isActive'])
            ->withTimestamps();
    }
    public function groupEmployeeAssignments()
    {
        return $this->belongsToMany(AllowanceGroupEmployeePivot::class, 'group_category_employee_allowances')
            ->withPivot(['amount', 'effective_from', 'status'])
            ->withTimestamps();
    }

}
