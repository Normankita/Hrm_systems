<?php

namespace App\Models;

use App\Http\Utils\Traits\onBootTrait;
use App\Models\Scopes\AuthUserCompanyScope;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
use onBootTrait;

    public function leaveTypes()
    {
        return $this->belongsToMany(LeaveType::class, 'leave_type_role');
    }
}
