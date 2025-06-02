<?php

namespace App\Http\Utils;

class Helpers {
    public static function currencyFormat($amount) {
        return number_format($amount, 0, ',', '.');
    }
}
