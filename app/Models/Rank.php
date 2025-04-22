<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rank extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'code', 'description'
    ];

    public function roleMappings()
    {
        return $this->hasMany(DesignationRoleMapping::class);
    }


}
