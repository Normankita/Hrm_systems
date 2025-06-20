<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Event extends Model
{
    protected $fillable = ['eventable_id',
     'eventable_type', 'user_id', 'type', 'data'];

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
