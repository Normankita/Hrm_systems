<?php

function format_currency($amount, $currency = 'TZS', $decimals = 0)
{
    return number_format($amount, $decimals) . ' ' . strtoupper($currency);
}
