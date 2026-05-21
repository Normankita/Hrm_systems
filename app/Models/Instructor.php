<?php

namespace App\Models;

use App\Http\Utils\Traits\onBootTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Instructor extends Model
{
    use onBootTrait;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'specialization',
        'employee_id',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function trainings(): BelongsToMany
    {
        return $this->belongsToMany(Training::class, 'instructor_training')->withTimestamps();
    }

    public function displayLabel(): string
    {
        $label = $this->name;
        if ($this->specialization) {
            $label .= ' (' . $this->specialization . ')';
        }

        return $label;
    }
}
