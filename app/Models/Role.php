<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'code', 'description'
    ];

    public function roleMappings()
    {
        return $this->hasMany(DesignationRoleMapping::class);
    }

    //  Optional: direct access to designations through the mappping
    // public function designations()
    // {
    //     return $this->hasManyThrough(
    //         Designation::class,
    //         DesignationRoleMapping::class,
    //         'role_id',          // Foreign key on pivot
    //         'id',               // Foreign key on designation
    //         'id',               // Local key on role
    //         'designation_id'    // Local key on pivot
    //     );
    // }
}
