<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface CryptocurrenciesRepository
{
    public static function get(string $cryptocurrencySymbols, string $currencyConvertType): Collection;
}
