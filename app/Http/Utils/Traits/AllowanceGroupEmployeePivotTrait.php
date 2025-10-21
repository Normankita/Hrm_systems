<?php

namespace App\Http\Utils\Traits;

use App\Models\AllowanceGroupEmployeePivot;

trait AllowanceGroupEmployeePivotTrait
{
    public function frequency($id) {
        $allObj = AllowanceGroupEmployeePivot::find($id);
        dd($allObj);
    }
}
