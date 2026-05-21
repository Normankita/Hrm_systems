<?php

namespace App\Models;

use App\Http\Utils\Traits\GeneratesReferenceNumber;
use App\Http\Utils\Traits\onBootTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EmployeeRelationResolution extends Model
{
    use GeneratesReferenceNumber, onBootTrait;

    protected $fillable = [
        'company_id', 'reference_number', 'resolvable_type', 'resolvable_id',
        'title', 'summary', 'action_taken', 'status', 'resolved_at', 'resolved_by', 'created_by',
    ];

    protected $casts = ['resolved_at' => 'datetime'];

    public function resolvable(): MorphTo
    {
        return $this->morphTo();
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function resolvableTypes(): array
    {
        return [
            EmployeeComplaint::class => 'Complaint',
            EmployeeDiscipline::class => 'Discipline',
            EmployeeConflict::class => 'Conflict',
        ];
    }
}
