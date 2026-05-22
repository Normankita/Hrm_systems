<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DesignationRoleMapping extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'designation_id', 'role_id'
    ];

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
