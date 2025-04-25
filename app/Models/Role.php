<?php

namespace App\Models;

use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    public function leaveTypes()
    {
        return $this->belongsToMany(LeaveType::class, 'leave_type_role');
    }
}
