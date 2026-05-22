<?php

namespace App\Http\Utils\Traits;

use App\Models\Contribution;
use Illuminate\Support\Collection;

trait ContributionDivisionTrait
{
    public static function getContributionPercent($name, Collection $contributions)
    {
        if ($name == "company_nssf") {
            return $contributions->where('name', 'NSSF')->first()->company_percent;
        } elseif ($name == "company_psssf") {
            return $contributions->where('name', 'PSSSF')->first()->company_percent;
        } elseif ($name == "company_paye") {
            return $contributions->where('name', 'PAYE')->first()->company_percent;
        } elseif ($name == "company_sdl") {
            return $contributions->where('name', 'SDL')->first()->company_percent;
        } elseif ($name == "company_wcf") {
            return $contributions->where('name', 'WCF')->first()->company_percent;
        } elseif ($name == "employee_nssf") {
            return $contributions->where('name', 'NSSF')->first()->employee_percent;
        } elseif ($name == "employee_psssf") {
            return $contributions->where('name', 'PSSSF')->first()->employee_percent;
        } elseif ($name == "employee_paye") {
            return $contributions->where('name', 'PAYE')->first()->employee_percent;
        } elseif ($name == "employee_sdl") {
            return $contributions->where('name', 'SDL')->first()->employee_percent;
        } elseif ($name == "employee_wcf") {
            return $contributions->where('name', 'WCF')->first()->employee_percent;
        } else {
            return null;
        }
    }
}