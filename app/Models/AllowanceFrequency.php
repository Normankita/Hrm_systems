<?php

namespace App\Models;

use App\Http\Utils\Traits\onBootTrait;
use App\Models\Scopes\AuthUserCompanyScope;
use Illuminate\Database\Eloquent\Model;

class AllowanceFrequency extends Model
{
    use onBootTrait;

    protected $fillable = [
        'name',
        'company_id',
        'base_category',
        'no_base_times',
        'no_times',
        'days_apart',
    ];
}
