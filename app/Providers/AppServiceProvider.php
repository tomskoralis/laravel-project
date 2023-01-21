<?php

namespace App\Providers;

use App\Repositories\BankExchangeRatesRepository;
use App\Repositories\CoinMarketCapCryptocurrenciesRepository;
use App\Repositories\CryptocurrenciesRepository;
use App\Repositories\ExchangeRatesRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CryptocurrenciesRepository::class, CoinMarketCapCryptocurrenciesRepository::class);
        $this->app->bind(ExchangeRatesRepository::class, BankExchangeRatesRepository::class);
    }

    public function boot(): void
    {

    }
}
