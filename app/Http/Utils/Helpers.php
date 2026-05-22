<?php

namespace App\Http\Utils;

use Illuminate\Support\Facades\Request;

class Helpers
{
    public static function currencyFormat($amount)
    {
        return number_format($amount, 0, ',', '.');
    }

    public static function sanitizeRequestNumbers($request)
    {
        $data = $request->all();

        foreach ($data as $key => $value) {
            // Only process strings
            if (is_string($value) && preg_match('/^\d{1,3}(,\d{3})*(\.\d+)?$/', $value)) {
                $data[$key] = str_replace(',', '', $value);
            }
        }
        // Optional: merge back into request if you want to override
        $request->merge($data);
    }
}
