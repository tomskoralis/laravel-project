<?php

namespace App\Http\Controllers\Cryptocurrency;

use App\Http\Controllers\Controller;
use App\Jobs\GetCryptocurrenciesJob;
use App\Jobs\GetExchangeRatesJob;
use App\Models\Account;
use App\Models\NonEloquent\ExchangeRate;
use App\Models\Transaction;
use App\Models\User;
use App\Rules\SecurityCodeValid;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CryptocurrencyBuyController extends Controller
{
    private const CURRENCY = 'EUR';

    public function show(Request $request): View
    {
        $cryptocurrencySymbol = strtoupper($request->symbol);

        GetCryptocurrenciesJob::dispatch($cryptocurrencySymbol, self::CURRENCY);
        $cryptocurrency = Cache::get($cryptocurrencySymbol);

        $cryptocurrencyName = $cryptocurrency ? $cryptocurrency->getName() : 'Unknown';

        /** @var User $user */
        $user = auth()->user();

        $accounts = $user->accounts()
            ->whereNull('closed_at')
            ->where('type', 'regular')
            ->get();

        $cryptoAccounts = $user->accounts()
            ->whereNull('closed_at')
            ->where([
                ['type', 'crypto'],
                ['currency', $cryptocurrencySymbol],
            ])->get();

        $securityCodeNumber = $user->securityCodes()
            ->inRandomOrder()
            ->limit(1)
            ->get()
            ->value('number');

        session(['securityCodeNumber' => $securityCodeNumber]);

        return view('cryptocurrency.buy')
            ->with([
                'cryptocurrency' => $cryptocurrency,
                'cryptocurrencyName' => $cryptocurrencyName,
                'convertTo' => self::CURRENCY,
                'accounts' => $accounts,
                'cryptoAccounts' => $cryptoAccounts,
                'securityCodeNumber' => $securityCodeNumber,
            ]);
    }

    public function buy(Request $request): RedirectResponse
    {
        $cryptocurrencySymbol = strtoupper($request->symbol);

        /** @var User $user */
        $user = auth()->user();

        $fromAccount = $user->accounts()
            ->whereNull('closed_at')
            ->findOrFail($request->from_account_id);

        if ($fromAccount->user_id !== $user->id) {
            abort(403);
        }

        GetExchangeRatesJob::dispatch();

        if (!Cache::has('rates')) {
            return redirect()->back()->with('status', 'failed-to-buy');
        }

        /** @var ExchangeRate $exchangeRate */
        $exchangeRate = (new Collection(Cache::get('rates')))
            ->filter(function ($rate) use ($fromAccount) {
                return $rate->getCurrency() === $fromAccount->currency;
            })->first();

        $cryptoAccounts = $user->accounts()->whereNull('closed_at')->where([
            ['type', 'crypto'],
            ['currency', $cryptocurrencySymbol],
        ])->get();

        GetCryptocurrenciesJob::dispatch($cryptocurrencySymbol, self::CURRENCY);

        if (!Cache::has($cryptocurrencySymbol)) {
            return redirect()->back()->with('status', 'failed-to-buy');
        }

        $cryptocurrency = Cache::get($cryptocurrencySymbol);

        $price = $cryptocurrency->getPrice() * $exchangeRate->getRate();

        $validated = $request->validateWithBag('buyCryptocurrency', [
            'from_account_id' => [
                'required',
                'numeric',
                'exists:accounts,id',
            ],
            'to_account_id' => [
                'required',
                'different:from_account_id',
                Rule::in($cryptoAccounts->pluck('id')->add('new')->all()),
            ],
            'amount' => [
                'required',
                'numeric',
                'regex:/^\d*(?:\.\d{1,8})?$/',
                'min:' . round(0.01 / $price, 8),
                'max:' . round($fromAccount->balance / $price, 8)
            ],
            'security_code' => [
                'required',
                'string',
                new SecurityCodeValid,
            ],
        ]);

        if (
            $request->to_account_id === 'new' &&
            !$user->accounts()
                ->whereNull('closed_at')
                ->where('id', $validated['to_account_id'])
                ->exists()
        ) {
            $toAccount = (new Account)->fill([
                'type' => 'crypto',
                'number' => 'LV' . $this->generateAccountNumber(),
                'currency' => $cryptocurrency->getSymbol(),
                'label' => $this->generateAccountLabel($user, $cryptocurrency->getName()),
            ]);
            $toAccount->user()->associate($user);
            $toAccount->save();
        } else {
            $toAccount = $user->accounts()
                ->whereNull('closed_at')
                ->where('id', $validated['to_account_id'])
                ->firstOrFail();
        }

        Transaction::query()->create([
            'outgoing_amount' => round($price * (float)$validated['amount'], 2),
            'incoming_amount' => (float)$validated['amount'],
            'from_account_id' => $validated['from_account_id'],
            'to_account_id' => $toAccount->id,
        ]);

        return redirect()->back()->with('status', 'cryptocurrency-bought');
    }

    private function generateAccountNumber(): int
    {
        $number = mt_rand(100000000, 999999999);
        if (Account::query()->where('number', $number)->exists()) {
            return $this->generateAccountNumber();
        }
        return $number;
    }

    private function generateAccountLabel(User $user, string $cryptocurrencyName): string
    {
        if (
            !$user->accounts()
                ->whereNull('closed_at')
                ->where('label', $cryptocurrencyName)
                ->exists()
        ) {
            return $cryptocurrencyName;
        }

        $labels = $user
            ->accounts()
            ->whereNull('closed_at')
            ->pluck('label')
            ->all();

        $i = 2;
        while (in_array($cryptocurrencyName . $i, $labels)) {
            $i++;
        }
        return $cryptocurrencyName . $i;
    }
}
