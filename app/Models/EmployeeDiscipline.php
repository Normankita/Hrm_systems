<?php

namespace App\Models;

use App\Http\Utils\Traits\GeneratesReferenceNumber;
use App\Http\Utils\Traits\onBootTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class EmployeeDiscipline extends Model
{
    use GeneratesReferenceNumber, onBootTrait;

    protected $fillable = [
        'company_id', 'reference_number', 'employee_id', 'action_type', 'description',
        'discipline_date', 'status', 'issued_by', 'created_by',
    ];

    protected $casts = ['discipline_date' => 'date'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function resolutions(): MorphMany
    {
        return $this->morphMany(EmployeeRelationResolution::class, 'resolvable');
    }
}
