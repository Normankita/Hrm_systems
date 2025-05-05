<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = ['filename', 'path', 'type'];

    public function attachmentable()
    {
        return $this->morphTo();
    }
}
