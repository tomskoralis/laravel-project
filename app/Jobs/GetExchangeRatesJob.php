<?php

namespace App\Jobs;

use App\Repositories\ExchangeRatesRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class GetExchangeRatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(ExchangeRatesRepository $exchangeRatesRepository): void
    {
        if (!Cache::has('rates')) {
            $this->cacheExchangeRates($exchangeRatesRepository->get());
        }
    }

    private function cacheExchangeRates(Collection $exchangeRates): void
    {
        $time = now()->greaterThan(now()->setTime(14, 5))
            ? now()->subDay()
            : now();
        Cache::put('rates', $exchangeRates, $time->diffInSeconds('14:05'));
    }
}
