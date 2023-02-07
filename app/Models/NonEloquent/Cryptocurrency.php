<?php

namespace App\Models\NonEloquent;
/*            (bool)$cryptocurrency->is_fiat,
            (bool)$cryptocurrency->is_active,
            $cryptocurrency->circulating_supply,
            $cryptocurrency->max_supply,
            implode(' ', $tags),
            $cryptocurrency->date_added,
            $cryptocurrency->last_updated,
            $cryptocurrency->quote->{$currencyConvertType}->price,
            $cryptocurrency->quote->{$currencyConvertType}->percent_change_1h,
            $cryptocurrency->quote->{$currencyConvertType}->percent_change_24h,
            $cryptocurrency->quote->{$currencyConvertType}->percent_change_7d,
            $cryptocurrency->quote->{$currencyConvertType}->market_cap,
            $cryptocurrency->quote->{$currencyConvertType}->market_cap_dominance,
            $cryptocurrency->quote->{$currencyConvertType}->fully_diluted_market_cap,
            $cryptocurrency->quote->{$currencyConvertType}->last_updated,*/

use Carbon\Carbon;

class Cryptocurrency
{
    private string $symbol;
    private string $name;
    private bool $isFiat;
    private bool $isActive;
    private float $circulatingSupply;
    private float $totalSupply;
    private array $tags;
    private string $addedAt;
    private string $updatedAt;
    private float $price;
    private float $change1h;
    private float $change24h;
    private float $change7d;
    private float $marketCap;
    private float $marketCapDominance;
    private float $fullyDilutedMarketCap;
    private string $quoteUpdatedAt;

    public function __construct(
        string $symbol,
        string $name,
        bool   $isFiat,
        bool   $isActive,
        float  $circulatingSupply,
        float  $totalSupply,
        array  $tags,
        string $addedAt,
        string $updatedAt,
        float  $price,
        float  $change1h,
        float  $change24h,
        float  $change7d,
        float  $marketCap,
        float  $marketCapDominance,
        float  $fullyDilutedMarketCap,
        string $quoteUpdatedAt
    )
    {
        $this->symbol = $symbol;
        $this->name = $name;
        $this->isFiat = $isFiat;
        $this->isActive = $isActive;
        $this->circulatingSupply = $circulatingSupply;
        $this->totalSupply = $totalSupply;
        $this->tags = $tags;
        $this->addedAt = $addedAt;
        $this->updatedAt = $updatedAt;
        $this->price = $price;
        $this->change1h = $change1h;
        $this->change24h = $change24h;
        $this->change7d = $change7d;
        $this->marketCap = $marketCap;
        $this->marketCapDominance = $marketCapDominance;
        $this->fullyDilutedMarketCap = $fullyDilutedMarketCap;
        $this->quoteUpdatedAt = $quoteUpdatedAt;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isFiat(): bool
    {
        return $this->isFiat;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getCirculatingSupply(): float
    {
        return $this->circulatingSupply;
    }

    public function getTotalSupply(): float
    {
        return $this->totalSupply;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getAddedAt(): string
    {
        return $this->addedAt;
    }

    public function getAddedAtFormatted(): string
    {
        return $this->formatTime($this->addedAt);
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function getUpdatedAtFormatted(): string
    {
        return $this->formatTime($this->updatedAt);
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getPriceFormatted(string $currency): string
    {
        return $this->getCurrencyFormatted($this->price, $currency);
    }

    public function getChange1h(): float
    {
        return $this->change1h;
    }

    public function getChange24h(): float
    {
        return $this->change24h;
    }

    public function getChange7d(): float
    {
        return $this->change7d;
    }

    public function getMarketCap(): float
    {
        return $this->marketCap;
    }

    public function getMarketCapFormatted(string $currency): string
    {
        return $this->getCurrencyFormatted($this->marketCap, $currency);
    }

    public function getMarketCapDominance(): float
    {
        return $this->marketCapDominance;
    }

    public function getFullyDilutedMarketCap(): float
    {
        return $this->fullyDilutedMarketCap;
    }

    public function getFullyDilutedMarketCapFormatted(string $currency): string
    {
        return $this->getCurrencyFormatted($this->fullyDilutedMarketCap, $currency);
    }

    public function getQuoteUpdatedAt(): string
    {
        return $this->quoteUpdatedAt;
    }

    public function getQuoteUpdatedAtFormatted(): string
    {
        return $this->formatTime($this->quoteUpdatedAt);
    }

    private function getCurrencyFormatted(float $amount, string $currency): string
    {
        $formatter = new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::CURRENCY);
        if ($amount < 1) {
            $formatter->setAttribute(\NumberFormatter::MAX_SIGNIFICANT_DIGITS, 4);
        }
        return $formatter->formatCurrency($amount, $currency);
    }

    private function formatTime(string $time): string
    {
        return auth()->user()->timezone
            ? Carbon::parse($time)
                ->setTimezone(auth()->user()->timezone)
                ->format('d/m/Y G:i')
            : Carbon::parse($time)
                ->format('d/m/Y G:i');
    }
}
