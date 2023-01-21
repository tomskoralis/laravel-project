<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface ExchangeRatesRepository
{
    public static function get(): Collection;
}
