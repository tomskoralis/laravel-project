<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\SecurityCode;
use App\Models\User;
use App\Rules\CurrencyValid;
use App\Rules\SecurityCodeValid;
use App\Rules\UserLabelUnique;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use function auth;

class AccountCreateController extends Controller
{
    public function create(): View
    {
        /** @var User $user */
        $user = auth()->user();

        $securityCodeNumber = SecurityCode::query()
            ->where('user_id', $user->id)
            ->inRandomOrder()
            ->limit(1)
            ->get()
            ->value('number');
        session(['securityCodeNumber' => $securityCodeNumber]);

        return view('account.create')->with(
            'securityCodeNumber', $securityCodeNumber
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validateWithBag('accountCreation', [
            'currency' => [
                'required',
                'string',
                'max:64',
                new CurrencyValid,
            ],
            'label' => [
                'max:64',
                new UserLabelUnique,
            ],
            'security_code' => [
                'required',
                'string',
                new SecurityCodeValid,
            ],
        ]);

        $currency = $this->replaceCurrencyName($request->currency);

        $account = (new Account)->fill([
            'type' => 'regular',
            'number' => 'LV' . $this->generateAccountNumber(),
            'currency' => $currency,
            'label' => $request->label,
        ]);
        $account->user()->associate(auth()->user());
        $account->save();

        return back()->with('status', 'account-created');
    }

    private function replaceCurrencyName(string $currency): string
    {
        return str_replace(
            [
                'AUSTRALIAN DOLLAR',
                'BULGARIAN LEV',
                'BRAZILIAN REAL',
                'CANADIAN DOLLAR',
                'SWISS FRANC',
                'YUAN RENMINBI',
                'CZECH KORUNA',
                'DANISH KRONE',
                'EURO',
                'POUND STERLING',
                'HONG KONG DOLLAR',
                'FORINT',
                'RUPIAH',
                'NEW ISRAELI SHEQEL',
                'INDIAN RUPEE',
                'ICELAND KRONA',
                'YEN',
                'WON',
                'MEXICAN PESO',
                'MALAYSIAN RINGGIT',
                'NORWEGIAN KRONE',
                'NEW ZEALAND DOLLAR',
                'PHILIPPINE PESO',
                'ZLOTY',
                'ROMANIAN LEU',
                'SWEDISH KRONA',
                'SINGAPORE DOLLAR',
                'BAHT',
                'TURKISH LIRA',
                'US DOLLAR',
                'RAND',
            ],
            [
                'AUD',
                'BGN',
                'BRL',
                'CAD',
                'CHF',
                'CNY',
                'CZK',
                'DKK',
                'EUR',
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
            ],
            strtoupper($currency)
        );
    }

    private function generateAccountNumber(): int
    {
        $number = mt_rand(100000000, 999999999);
        if (
            Account::query()
                ->where('number', $number)
                ->exists()
        ) {
            return $this->generateAccountNumber();
        }
        return $number;
    }
}
