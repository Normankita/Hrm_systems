<?php

namespace App\Models;

use App\Http\Utils\Traits\onBootTrait;
use App\Models\Scopes\AuthUserCompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Event extends Model
{

   use onBootTrait;
    protected $fillable = [
        'eventable_id',
        'eventable_type',
        'user_id',
        'type',
        'data',
        'company_id'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function eventable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
