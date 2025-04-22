<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public function user() {
        return $this->hasOne(User::class);
    }
    
    public function company() {
        return $this->belongsTo(Company::class);
    }
    
    public function department() {
        return $this->belongsTo(Department::class);
    }
    
    public function designation() {
        return $this->belongsTo(DesignationRoleMapping::class);
    }
    
    public function contract() {
        return $this->hasOne(EmployeeContract::class);
    }
    
    public function documents() {
        return $this->hasMany(EmployeeDocument::class);
    }
    
}
