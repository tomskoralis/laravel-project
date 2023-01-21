<?php

namespace App\Http\Controllers\Cryptocurrency;

use App\Http\Controllers\Controller;
use App\Jobs\GetCryptocurrenciesJob;
use App\Jobs\GetExchangeRatesJob;
use App\Models\Transaction;
use App\Rules\SecurityCodeValid;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class CryptocurrencySellController extends Controller
{
    private const CURRENCY = 'EUR';

    public function show(Request $request)
    {
        $cryptocurrencySymbol = strtoupper($request->symbol);

        GetCryptocurrenciesJob::dispatch($cryptocurrencySymbol, self::CURRENCY);
        $cryptocurrency = Cache::get($cryptocurrencySymbol);

        $cryptoAccounts = auth()->user()->accounts()->whereNull('closed_at')->where([
            ['type', 'crypto'],
            ['currency', $cryptocurrencySymbol],
        ])->get();
        $accounts = auth()->user()->accounts()->whereNull('closed_at')->where('type', 'regular')->get();
        $amountOwned = array_sum($cryptoAccounts->pluck('balance')->toArray());

        $securityCodeNumber = auth()->user()->securityCodes()->inRandomOrder()->limit(1)->get()->value('number');
        session(['securityCodeNumber' => $securityCodeNumber]);

        $cryptocurrencyName = $cryptocurrency ? $cryptocurrency->getName() : 'Unknown';
        return view('cryptocurrency.sell')
            ->with([
                'cryptocurrency' => $cryptocurrency,
                'cryptocurrencyName' => $cryptocurrencyName,
                'amountOwned' => $amountOwned,
                'accounts' => $accounts,
                'cryptoAccounts' => $cryptoAccounts,
                'securityCodeNumber' => $securityCodeNumber,
            ]);
    }

    public function sell(Request $request): RedirectResponse
    {
        $cryptocurrencySymbol = strtoupper($request->symbol);
        $fromAccount = auth()->user()->accounts()->whereNull('closed_at')->findOrFail($request->from_account_id);
        $toAccount = auth()->user()->accounts()->whereNull('closed_at')->findOrFail($request->to_account_id);

        if ($fromAccount->user_id !== auth()->user()->id || $toAccount->user_id !== auth()->user()->id) {
            abort(403);
        }

        GetExchangeRatesJob::dispatch();
        $exchangeRate = (new Collection(Cache::get('rates')))->filter(function($rate) use ($toAccount) {
            return $rate->getCurrency() === $toAccount->currency;
        })->first()->getRate();

        $cryptoAccounts = auth()->user()->accounts()->whereNull('closed_at')->where([
            ['type', 'crypto'],
            ['currency', $cryptocurrencySymbol],
        ])->get();

        GetCryptocurrenciesJob::dispatch($cryptocurrencySymbol, self::CURRENCY);
        $cryptocurrency = Cache::get($cryptocurrencySymbol);

        $validated = $request->validateWithBag('sellCryptocurrency', [
            'from_account_id' => [
                'required',
                'numeric',
                Rule::in($cryptoAccounts->pluck('id')->all()),
            ],
            'to_account_id' => [
                'required',
                'numeric',
                'different:from_account_id',
                'exists:accounts,id',
            ],
            'amount' => [
                'required',
                'numeric',
                'regex:/^\d*(?:\.\d{1,8})?$/',
                'min:' . round(0.01 / ($cryptocurrency->getPrice() * $exchangeRate), 8),
                'max:' . $fromAccount->balance,
            ],
            'security_code' => [
                'required',
                'string',
                new SecurityCodeValid,
            ],
        ]);

        Transaction::create([
            'outgoing_amount' => (float)$validated['amount'],
            'incoming_amount' => round($cryptocurrency->getPrice() * $exchangeRate * (float)$validated['amount'], 2),
            'from_account_id' => (int)$validated['from_account_id'],
            'to_account_id' => (int)$validated['to_account_id'],
        ]);

        return redirect()->back()->with('status', 'cryptocurrency-sold');
    }
}
