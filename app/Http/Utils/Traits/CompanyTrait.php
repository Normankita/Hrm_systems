<?php

namespace App\Http\Utils\Traits;

class CompanyTrait {


    /**
     * Check if the company has a setting with the given key
     *
     * @param string $key
     * @return bool
     */
    public static function hasSetting($key) {
        $authUser = auth()->user();
        $company = $authUser->company;
        return $company->settings()->where('name', $key)
        ->exists();
    }



    public static function isSetting($key, $value) {
        $authUser = auth()->user();
        $company = $authUser->company;
        return $company->settings()->where('name', $key)
            ->where('value', $value)
            ->exists();
    }
}
