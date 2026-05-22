<?php

namespace App\Models;

use App\Http\Utils\Traits\GeneratesReferenceNumber;
use App\Http\Utils\Traits\onBootTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class EmployeeConflict extends Model
{
    use GeneratesReferenceNumber, onBootTrait;

    protected $fillable = [
        'company_id', 'reference_number', 'employee_id', 'other_employee_id',
        'subject', 'description', 'conflict_date', 'severity', 'status', 'created_by',
    ];

    protected $casts = ['conflict_date' => 'date'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function otherEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'other_employee_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function resolutions(): MorphMany
    {
        return $this->morphMany(EmployeeRelationResolution::class, 'resolvable');
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(EmployeeRelationDocument::class, 'documentable');
    }
}
