<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingOptions extends Model
{
    protected $table = "settings_options";

    protected $fillable = [
        'key',
        'values'
    ];

    protected $casts = [
        'values' => 'array',
    ];
}
