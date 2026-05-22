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


    public function contributions(){
        return $this->hasMany(Contribution::class);
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
        return $this->hasMany(Setting::class);
    }


    public static function ownerCompanies()
    {
        $companyId = auth()->user()->company_id;
        return self::whereNotIn('id', [$companyId]);
    }

    
    public function roles() {
        return $this->hasMany(Role::class);
    }


    public function defaultShift() {
        return AttendanceSession::where('company_id', $this->id)
            ->where('is_active', true)
            ->first();
    }


    public function getMinimumAge() {
        return $this->settings()->where('name', 'minimum_age')
            ->first()->value ?? 18;
    }
}

