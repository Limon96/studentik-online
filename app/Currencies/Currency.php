<?php

namespace App\Currencies;

class Currency
{
    private static $decimals = 0;

    public static function format(float $number = 0, string $currency = 'RUB', $thousands_separator = ' ', string $decimal_separator = '.')
    {
        $class = 'App\\Currencies\\Currencies\\' . $currency;

        $currency = new $class(number_format($number, self::$decimals, $decimal_separator, $thousands_separator));

        return $currency->format();
    }

}
