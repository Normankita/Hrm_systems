<?php
namespace App\Models;

use App\Http\Utils\Traits\HasEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    use HasFactory, HasEvents;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'is_taxable',
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_allowance')
            ->withPivot(['amount', 'effective_from', 'status'])
            ->withTimestamps();
    }

    public function allowance_group_employee(){
        return $this->belongsToMany(AllowanceGroupEmployeePivot::class,'group_category_employee_allowance')
        ->withPivot('amount', 'effective_from', 'status')
        ->withTimestamps();
    }
}
