<?php

namespace App\Models;

use App\Http\Utils\Traits\GeneratesReferenceNumber;
use App\Http\Utils\Traits\onBootTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class EmployeeComplaint extends Model
{
    use GeneratesReferenceNumber, onBootTrait;

    protected $fillable = [
        'company_id', 'reference_number', 'employee_id', 'subject', 'description',
        'complaint_date', 'severity', 'status', 'reported_by', 'created_by',
    ];

    protected $casts = ['complaint_date' => 'date'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
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
