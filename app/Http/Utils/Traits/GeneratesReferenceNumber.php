<?php

namespace App\Http\Utils\Traits;

trait GeneratesReferenceNumber
{
    public static function nextReferenceNumber(string $prefix): string
    {
        $latest = self::latest('id')->first();
        $next = $latest ? $latest->id + 1 : 1;

        return $prefix . '-' . str_pad((string) $next, 6, '0', STR_PAD_LEFT);
    }
}
