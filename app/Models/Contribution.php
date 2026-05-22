<?php

namespace App\Models;

use App\Http\Utils\Traits\onBootTrait;
use App\Models\Scopes\AuthUserCompanyScope;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{

    use onBootTrait;

    protected $fillable = [
        'name',
        'percent',
        'description',
        'company_id',
        'employee_percent',
        'company_percent'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
