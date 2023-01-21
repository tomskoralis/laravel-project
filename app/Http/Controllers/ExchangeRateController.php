<?php

namespace App\Http\Controllers;

use App\Jobs\GetExchangeRatesJob;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ExchangeRateController extends Controller
{
    public function index(): View
    {
        GetExchangeRatesJob::dispatch();
        $exchangeRates = new Collection(Cache::get('rates'));

        $timeUpdatedAt = $exchangeRates->isEmpty()
            ? null
            : $exchangeRates->first()->timestampFormatted();

        return view('rates')
            ->with([
                'rates' => $exchangeRates,
                'timeUpdatedAt' => $timeUpdatedAt
            ]);
    }
}
