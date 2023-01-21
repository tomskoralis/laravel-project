<?php

namespace App\Repositories;

use App\Models\NonEloquent\Cryptocurrency;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use stdClass;

class CoinMarketCapCryptocurrenciesRepository implements CryptocurrenciesRepository
{
    private static array $instances = [];
    private static array $headers;
    private const BASE_URI = 'https://pro-api.coinmarketcap.com';

    public function __construct()
    {
        self::$headers = [
            'Accepts' => 'application/json',
            'X-CMC_PRO_API_KEY' => config('coinmarketcap.key'),
        ];
    }

    public static function get(string $cryptocurrencySymbols, string $currencyConvertType): Collection
    {
        $cryptocurrencies = new Collection();

        $response = Http::acceptJson()
            ->withHeaders(self::getInstance()::$headers)
            ->get(
                self::getInstance()::BASE_URI . '/v2/cryptocurrency/quotes/latest',
                [
                    'symbol' => $cryptocurrencySymbols,
                    'convert' => $currencyConvertType,
                ],
            );
        $response = json_decode($response->body());

        foreach ($response->data as $fetchedCryptocurrency) {
            if (isset($fetchedCryptocurrency[0])) {
                $cryptocurrencies->add(self::arrangeCryptocurrency($fetchedCryptocurrency[0], $currencyConvertType));
            }
        }

        return $cryptocurrencies;
    }

    private static function arrangeCryptocurrency(stdClass $cryptocurrency, string $currencyConvertType): Cryptocurrency
    {
        return new Cryptocurrency(
            $cryptocurrency->symbol,
            $cryptocurrency->name,
            $cryptocurrency->quote->{$currencyConvertType}->price,
            $cryptocurrency->quote->{$currencyConvertType}->percent_change_1h,
            $cryptocurrency->quote->{$currencyConvertType}->percent_change_24h,
            $cryptocurrency->quote->{$currencyConvertType}->percent_change_7d,
        );
    }

    private static function getInstance(): CoinMarketCapCryptocurrenciesRepository
    {
        $class = static::class;
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }
        return self::$instances[$class];
    }
}
