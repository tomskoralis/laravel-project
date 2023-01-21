<?php

namespace App\Models\NonEloquent;

class Cryptocurrency
{
    private string $symbol;
    private string $name;
    private float $price;
    private float $change1h;
    private float $change24h;
    private float $change7d;

    public function __construct(
        string $symbol,
        string $name,
        float  $price,
        float  $change1h,
        float  $change24h,
        float  $change7d,
    )
    {
        $this->symbol = $symbol;
        $this->name = $name;
        $this->price = $price;
        $this->change1h = $change1h;
        $this->change24h = $change24h;
        $this->change7d = $change7d;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
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

    public function getPriceFormatted(string $currency): string
    {
        $formatter = new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::CURRENCY);
        if ($this->price < 1) {
            $formatter->setAttribute(\NumberFormatter::MAX_SIGNIFICANT_DIGITS, 4);
        }
        return $formatter->formatCurrency($this->price, $currency);
    }
}
