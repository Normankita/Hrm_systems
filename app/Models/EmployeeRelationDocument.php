<?php

namespace App\Models;

use App\Http\Utils\Traits\onBootTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EmployeeRelationDocument extends Model
{
    use onBootTrait;

    protected $fillable = [
        'company_id',
        'documentable_type',
        'documentable_id',
        'file_path',
        'original_name',
        'uploaded_by',
    ];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
