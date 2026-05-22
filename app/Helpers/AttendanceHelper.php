<?php

use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

if (!function_exists('canCheckAttendance')) {
    function canCheckAttendance() {
        $user = Auth::user();
        return $user
            && $user->company
            && $user->company->settings
            && $user->hasRole('EMPLOYEE')
            && $user->company->settings()->where('name' , 'user_check_attendance')
            ->where('value', 'yes')
            ->exists();
    }
}
