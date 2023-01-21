<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Account>
 */
class AccountFactory extends Factory
{
    public function definition(): array
    {
        $currencies = [
            'AUD',
            'BGN',
            'BRL',
            'CAD',
            'CHF',
            'CNY',
            'CZK',
            'DKK',
            'GBP',
            'HKD',
            'HUF',
            'IDR',
            'ILS',
            'INR',
            'ISK',
            'JPY',
            'KRW',
            'MXN',
            'MYR',
            'NOK',
            'NZD',
            'PHP',
            'PLN',
            'RON',
            'SEK',
            'SGD',
            'THB',
            'TRY',
            'USD',
            'ZAR',
        ];
        return [
            'number' => 'LV'. fake()->unique()->randomNumber(9, true),
            'currency' => $currencies[array_rand($currencies)],
            'balance' => fake()->randomFloat(2, 0, 999),
        ];
    }
}
