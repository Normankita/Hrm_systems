<?php

namespace App\Models;

use App\Http\Utils\Traits\onBootTrait;
use App\Models\Scopes\AuthUserCompanyScope;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use onBootTrait;

    protected $fillable = [
        'name',
        'value',
        'company_id'
    ];

    /**
     * In attendance, there should be following settings:
     */
}
