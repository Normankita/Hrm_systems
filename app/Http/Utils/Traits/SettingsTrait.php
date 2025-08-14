<?php

namespace App\Http\Utils\Traits;

use App\Models\Setting;

class SettingsTrait
{
    // This trait can be used to handle settings-related functionalities
    // such as retrieving and updating application settings.

    public static function getSettings()
    {
        // Logic to retrieve application settings
    }

    public static function updateSettings($data)
    {
        // Logic to update application settings
    }

    public static function getCompanyInfo()
    {
        // Logic to retrieve company information
    }

    /**
     * Thisk function will check the attendance type
     * and return 'daily' or 'shift' based on the setting.
     */
    public static function getAttendanceType() {
        $attendencaType = Setting::where('name', 'attendance_type')
            ->first();
        if ($attendencaType) {
            return $attendencaType->value;
        }
        return 'daily';
    }

    public static function getArrivalTime()
    {
        // Logic to retrieve the arrival time setting
        $arrivalTime = Setting::where('name', 'arrival_time')
            ->first();
        return $arrivalTime ? $arrivalTime->value : '8:00 AM';
    }
}
