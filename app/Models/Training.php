<?php

namespace App\Models;

use App\Http\Utils\Traits\onBootTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    use onBootTrait;

    protected $fillable = [
        'name',
        'description',
        'type',
        'status',
        'duration',
        'location',
        'start_date',
        'end_date',
        'company_id',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_training')->withTimestamps();
    }

    public function instructors(): BelongsToMany
    {
        return $this->belongsToMany(Instructor::class, 'instructor_training')->withTimestamps();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(TrainingParticipant::class);
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'training_participants')
            ->withPivot(['status', 'enrolled_at', 'completed_at', 'department_id', 'notes'])
            ->withTimestamps();
    }

    public function instructorNames(): string
    {
        return $this->instructors->pluck('name')->implode(', ') ?: '—';
    }

    public function departmentNames(): string
    {
        return $this->departments->pluck('name')->implode(', ') ?: 'All';
    }

    public function enrolledCount(): int
    {
        return $this->participants()->count();
    }

    public function completedCount(): int
    {
        return $this->participants()->where('status', 'Completed')->count();
    }
}
