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
            ->withPivot(['amount', 'effective_from', 'effective_to', 'frequency', 'status'])
            ->withTimestamps();
    }

    public function allowance_groups()
    {
        return $this->belongsToMany(AllowanceGroup::class);
    }
}
