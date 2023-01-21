<?php

namespace App\Http\Controllers\Cryptocurrency;

use App\Http\Controllers\Controller;

use App\Jobs\GetCryptocurrenciesJob;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class CryptocurrencyReadController extends Controller
{
    private const CURRENCY = 'EUR';
    private const SYMBOLS = ['BTC', 'ETH', 'BNB', 'XRP', 'DOGE', 'ADA', 'MATIC', 'DOT', 'LTC', 'SHIB'];

    public function index(): View
    {
        GetCryptocurrenciesJob::dispatch(self::SYMBOLS, self::CURRENCY);

        $cryptocurrencies = new Collection();
        foreach (self::SYMBOLS as $symbol) {
            $cryptocurrencies->add(Cache::get($symbol));
        }

        return view('cryptocurrency.index')
            ->with([
                'cryptocurrencies' => $cryptocurrencies,
                'convertTo' => self::CURRENCY,
            ]);
    }

    public function show(Request $request): View
    {
        GetCryptocurrenciesJob::dispatch($request->symbol, self::CURRENCY);

        $cryptocurrency = Cache::get($request->symbol);

        return view('cryptocurrency.show')
            ->with([
                'cryptocurrency' => $cryptocurrency,
                'convertTo' => self::CURRENCY,
            ]);
    }
}
