<?php

namespace App\Http\Controllers\Cryptocurrency;

use App\Http\Controllers\Controller;
use App\Jobs\GetCryptocurrenciesJob;
use App\Jobs\GetExchangeRatesJob;
use App\Models\Account;
use App\Models\Transaction;
use App\Rules\SecurityCodeValid;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class CryptocurrencyBuyController extends Controller
{
    private const CURRENCY = 'EUR';

    public function show(Request $request)
    {
        $cryptocurrencySymbol = strtoupper($request->symbol);

        GetCryptocurrenciesJob::dispatch($cryptocurrencySymbol, self::CURRENCY);
        $cryptocurrency = Cache::get($cryptocurrencySymbol);
        $cryptocurrencyName = $cryptocurrency ? $cryptocurrency->getName() : 'Unknown';

        $accounts = auth()->user()->accounts()->whereNull('closed_at')->where('type', 'regular')->get();
        $cryptoAccounts = auth()->user()->accounts()->whereNull('closed_at')->where([
            ['type', 'crypto'],
            ['currency', $cryptocurrencySymbol],
        ])->get();

        $securityCodeNumber = auth()->user()->securityCodes()->inRandomOrder()->limit(1)->get()->value('number');
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

        $fromAccount = auth()->user()->accounts()->whereNull('closed_at')->findOrFail($request->from_account_id);

        if ($fromAccount->user_id !== auth()->user()->id) {
            abort(403);
        }

        GetExchangeRatesJob::dispatch();
        $exchangeRate = (new Collection(Cache::get('rates')))->filter(function($rate) use ($fromAccount) {
            return $rate->getCurrency() === $fromAccount->currency;
        })->first()->getRate();

        $cryptoAccounts = auth()->user()->accounts()->whereNull('closed_at')->where([
            ['type', 'crypto'],
            ['currency', $cryptocurrencySymbol],
        ])->get();

        GetCryptocurrenciesJob::dispatch($cryptocurrencySymbol, self::CURRENCY);
        $cryptocurrency = Cache::get($cryptocurrencySymbol);

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
                'min:' . round(0.01 / ($cryptocurrency->getPrice() * $exchangeRate), 8),
                'max:' . round($fromAccount->balance / ($cryptocurrency->getPrice() * $exchangeRate), 8)
            ],
            'security_code' => [
                'required',
                'string',
                new SecurityCodeValid,
            ],
        ]);

        if (
            $request->to_account_id === 'new' &&
            !auth()->user()->accounts()->whereNull('closed_at')->where('id', $validated['to_account_id'])->exists()
        ) {
            $toAccount = (new Account)->fill([
                'type' => 'crypto',
                'number' => 'LV' . $this->generateAccountNumber(),
                'currency' => $cryptocurrency->getSymbol(),
                'label' => $this->generateAccountLabel($cryptocurrency->getName()),
            ]);
            $toAccount->user()->associate(auth()->user());
            $toAccount->save();
        } else {
            $toAccount = auth()->user()->accounts()->whereNull('closed_at')->where('id', $validated['to_account_id'])->firstOrFail();
        }

        Transaction::create([
            'outgoing_amount' => round($cryptocurrency->getPrice() * $exchangeRate * (float)$validated['amount'], 2),
            'incoming_amount' => (float)$validated['amount'],
            'from_account_id' => $validated['from_account_id'],
            'to_account_id' => $toAccount->id,
        ]);

        return redirect()->back()->with('status', 'cryptocurrency-bought');
    }

    private function generateAccountNumber(): int
    {
        $number = mt_rand(100000000, 999999999);
        if (Account::where('number', $number)->exists()) {
            return $this->generateAccountNumber();
        }
        return $number;
    }

    private function generateAccountLabel(string $cryptocurrencyName): string
    {
        if (!auth()->user()->accounts()->whereNull('closed_at')->where('label', $cryptocurrencyName)->exists()) {
            return $cryptocurrencyName;
        }

        $labels = auth()->user()->accounts()->whereNull('closed_at')->pluck('label')->all();

        $i = 2;
        while (in_array($cryptocurrencyName . $i, $labels)) {
            $i++;
        }
        return $cryptocurrencyName . $i;
    }
}
