<?php

namespace App\Models\NonEloquent;

use Carbon\Carbon;
use function auth;

class ExchangeRate
{
    private string $currency;
    private float $rate;
    private string $updated_at;

    public function __construct(string $currency, float $rate, string $updated_at)
    {
        $this->currency = $currency;
        $this->rate = $rate;
        $this->updated_at = $updated_at;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function currencyFormatted(): string
    {
        $symbol = (new \NumberFormatter(
            \Locale::getDefault() . '@currency=' . $this->currency,
            \NumberFormatter::CURRENCY)
        )->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);

        if ($symbol === $this->currency) {
            return $this->currency;
        }
        return "$this->currency ($symbol)";
    }

    public function rateFormatted(): string
    {
        return rtrim(rtrim($this->rate, '0'), '.');
    }

    public function timestampFormatted(): string
    {
        $updatedAt = Carbon::parse($this->updated_at);
        $timezone = auth()->user()->timezone;
        return $timezone
            ? $updatedAt->timezone($timezone)->format('d/m/Y G:i')
            : $updatedAt->format('d/m/Y G:i');
    }
}
