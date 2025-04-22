<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeContract extends Model
{
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    
    public function supervisor() {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }
    
}
