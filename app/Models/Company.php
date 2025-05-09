<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'contact_number',
        'email',
        'brela_reg_number',
        'tin_number',
        'isActive'
    ];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function designations()
    {
        return $this->hasMany(Designation::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function admin()
    {
        return $this->hasOne(User::class, 'company_id', 'id')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'ADMIN');
            });
    }

    public function leaveTypes()
    {
        return $this->hasMany(LeaveType::class);
    }

    public function settings()
    {
        // return $this->hasOne(Setting::class);
    }

}

