<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{

    protected $fillable = [
        'name',
        'percent',
        'description',
        'company_id',
    ];

    public function company(){
        return $this->belongsTo(Company::class);
    }
}
