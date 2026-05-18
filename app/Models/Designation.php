<?php
namespace App\Models;

use App\Http\Utils\Traits\onBootTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
{
    use SoftDeletes, onBootTrait;

    protected $fillable = [
        'company_id', 'department_id', 'name',
        'description', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function roleMappings()
    {
        return $this->hasMany(DesignationRoleMapping::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Rank::class, DesignationRoleMapping::class);
    }

    public static function getOrCreateDesignation(String $name, int $departmentId) 
    {
        $designation = self::where('name', $name)->first();
        if (!$designation) {
            $designation = self::create([
                'name' => $name,
                'department_id' => $departmentId,
                'company_id' => auth()->user()->company_id,
            ]);
        }
        return $designation;
    }
}

