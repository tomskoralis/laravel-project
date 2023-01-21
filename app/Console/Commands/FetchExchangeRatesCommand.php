<?php

namespace App\Console\Commands;

use App\Repositories\ExchangeRatesRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as ConsoleCommand;

class FetchExchangeRatesCommand extends Command
{
    protected $signature = 'fetch:exchangeRates';

    protected $description = 'Fetch currency exchange rates from bank.lv';

    public function handle(ExchangeRatesRepository $exchangeRatesRepository): int
    {
        $this->cacheExchangeRates($exchangeRatesRepository->get()->toArray());
        return ConsoleCommand::SUCCESS;
    }

    private function cacheExchangeRates(array $exchangeRates): void
    {
        $time = now()->greaterThan(now()->setTime(14, 5))
            ? now()->subDay()
            : now();
        Cache::put('rates', $exchangeRates, $time->diffInSeconds('14:05'));
    }
}
