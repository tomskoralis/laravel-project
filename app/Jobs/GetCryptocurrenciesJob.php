<?php

namespace App\Jobs;

use App\Repositories\CryptocurrenciesRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class GetCryptocurrenciesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string|array $cryptocurrencySymbols;
    private string $currencyConvertType;
    private const TTL = 60;

    public function __construct(array|string $cryptocurrencySymbols, string $currencyConvertType)
    {
        $this->cryptocurrencySymbols = $cryptocurrencySymbols;
        $this->currencyConvertType = $currencyConvertType;
    }

    public function handle(CryptocurrenciesRepository $cryptocurrenciesRepository): void
    {
        $cryptocurrencyFound = true;

        if (gettype($this->cryptocurrencySymbols) === 'array') {
            foreach ($this->cryptocurrencySymbols as $symbol) {
                if ((string)$symbol && !Cache::has(strtoupper((string)$symbol))) {
                    $cryptocurrencyFound = false;
                    break;
                }
            }
            $this->cryptocurrencySymbols = strtoupper(join(',', $this->cryptocurrencySymbols));
        } else {
            $this->cryptocurrencySymbols = strtoupper($this->cryptocurrencySymbols);
            if (!Cache::has($this->cryptocurrencySymbols)) {
                $cryptocurrencyFound = false;
            }
        }

        if (!$cryptocurrencyFound) {
            $cryptocurrencies = $cryptocurrenciesRepository::get(
                $this->cryptocurrencySymbols,
                $this->currencyConvertType
            );
            foreach ($cryptocurrencies as $cryptocurrency) {
                Cache::put($cryptocurrency->getSymbol(), $cryptocurrency, self::TTL);
            }
        }
    }
}
