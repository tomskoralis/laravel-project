<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class CurrencyValid implements InvokableRule
{
    public function __invoke($attribute, $value, $fail): void
    {
        if (
            !in_array(
                strtoupper($value),
                [
                    'AUD', 'AUSTRALIAN DOLLAR',
                    'BGN', 'BULGARIAN LEV',
                    'BRL', 'BRAZILIAN REAL',
                    'CAD', 'CANADIAN DOLLAR',
                    'CHF', 'SWISS FRANC',
                    'CNY', 'YUAN RENMINBI',
                    'CZK', 'CZECH KORUNA',
                    'DKK', 'DANISH KRONE',
                    'EUR', 'EURO',
                    'GBP', 'POUND STERLING',
                    'HKD', 'HONG KONG DOLLAR',
                    'HUF', 'FORINT',
                    'IDR', 'RUPIAH',
                    'ILS', 'NEW ISRAELI SHEQEL',
                    'INR', 'INDIAN RUPEE',
                    'ISK', 'ICELAND KRONA',
                    'JPY', 'YEN',
                    'KRW', 'WON',
                    'MXN', 'MEXICAN PESO',
                    'MYR', 'MALAYSIAN RINGGIT',
                    'NOK', 'NORWEGIAN KRONE',
                    'NZD', 'NEW ZEALAND DOLLAR',
                    'PHP', 'PHILIPPINE PESO',
                    'PLN', 'ZLOTY',
                    'RON', 'ROMANIAN LEU',
                    'SEK', 'SWEDISH KRONA',
                    'SGD', 'SINGAPORE DOLLAR',
                    'THB', 'BAHT',
                    'TRY', 'TURKISH LIRA',
                    'USD', 'US DOLLAR',
                    'ZAR', 'RAND',
                ]
            )
        ) {
            $fail('The :attribute is invalid.');
        }
    }
}
