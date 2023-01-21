<?php

namespace App\Repositories;

use App\Models\NonEloquent\ExchangeRate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class BankExchangeRatesRepository implements ExchangeRatesRepository
{
    private static array $instances = [];
    private const URL = 'https://www.bank.lv/vk/ecb.xml';

    public static function get(): Collection
    {
        $xml = Http::get(self::getInstance()::URL);
        $response = json_decode(json_encode(simplexml_load_string($xml)), true);

        $exchangeRates = new Collection();
        if (!isset($response['Currencies']['Currency'])) {
            return $exchangeRates;
        }

        $exchangeRates->add(new ExchangeRate('EUR', 1, now()->toDateTimeString()));
        foreach ($response['Currencies']['Currency'] as $exchangeRate) {
            $exchangeRates->add(
                new ExchangeRate(
                    $exchangeRate['ID'],
                    (float)$exchangeRate['Rate'],
                    now()->toDateTimeString()
                )
            );
        }
        return $exchangeRates;
    }

    private static function getInstance(): BankExchangeRatesRepository
    {
        $class = static::class;
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }
        return self::$instances[$class];
    }
}
