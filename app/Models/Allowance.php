<?php
namespace App\Models;

use App\Http\Utils\Traits\HasEvents;
use App\Http\Utils\Traits\onBootTrait;
use App\Models\Scopes\AuthUserCompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    use HasFactory, HasEvents, onBootTrait;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'is_taxable',
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_allowance')
            ->withPivot(['id', 'amount', 'effective_from', 'status', 'effective_from'])
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
