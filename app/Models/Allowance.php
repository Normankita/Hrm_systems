<?php
namespace App\Models;

use App\Http\Utils\Traits\HasEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    use HasFactory;

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
}
