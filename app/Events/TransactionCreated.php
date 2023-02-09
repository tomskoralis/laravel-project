<?php

namespace App\Events;

use App\Jobs\GetCryptocurrenciesJob;
use App\Jobs\GetExchangeRatesJob;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class TransactionCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private const CURRENCY = 'EUR';

    public function __construct(Transaction $transaction)
    {
        $fromAccount = Account::where('id', $transaction->from_account_id);
        $toAccount = Account::where('id', $transaction->to_account_id);
        $outgoingAmount = (float)$transaction->outgoing_amount;

        if ($fromAccount->value('currency') === $toAccount->value('currency')) {
            $incomingAmount = $outgoingAmount;
        } elseif ($fromAccount->value('type') === 'regular' && $toAccount->value('type') === 'regular') {
            $incomingAmount = round(
                $this->convertCurrency(
                    $outgoingAmount,
                    $fromAccount->value('currency'),
                    $toAccount->value('currency')
                ),
                2
            );
        } elseif ($transaction->incoming_amount) {
            $incomingAmount = $transaction->incoming_amount;
        } else {
            $fromCryptocurrencySymbol = $fromAccount->value('type') === 'crypto'
                ? $fromAccount->value('currency')
                : '';
            $toCryptocurrencySymbol = $toAccount->value('type') === 'crypto'
                ? $toAccount->value('currency')
                : '';

            GetCryptocurrenciesJob::dispatch(
                [$fromCryptocurrencySymbol, $toCryptocurrencySymbol],
                self::CURRENCY
            );
            $fromCurrency = Cache::get($fromCryptocurrencySymbol);
            $toCurrency = Cache::get($toCryptocurrencySymbol);

            $incomingAmount = $fromCurrency === null
                ? $this->convertCurrency($outgoingAmount, $fromAccount->value('currency'))
                : $outgoingAmount * $fromCurrency->getPrice();
            $incomingAmount = $toCurrency === null
                ? $incomingAmount * $this->getExchangeRate($toAccount->value('currency'))
                : $incomingAmount / $toCurrency->getPrice();

            $incomingAmount = $toAccount->value('type') === 'crypto'
                ? round($incomingAmount, 8)
                : round($incomingAmount, 2);
        }

        if ($incomingAmount > 0) {
            $fromAccountBalanceBefore = $fromAccount->value('balance');;
            $toAccountBalanceBefore = $toAccount->value('balance');

            $fromAccount->decrement('balance', $transaction->outgoing_amount);
            $toAccount->increment('balance', $incomingAmount);

            if ($toAccountBalanceBefore === $toAccount->value('balance')) {
                $fromAccount->fill(['balance' => $fromAccountBalanceBefore]);
            } else {
                $transaction = $transaction->fill(['incoming_amount' => $incomingAmount]);
                $transaction->save();
            }
        }
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('channel-name');
    }

    private function convertCurrency(float $amount, string $from, string $to = self::CURRENCY): float
    {
        if ($from !== self::CURRENCY) {
            $amount /= $this->getExchangeRate($from);
        }
        if ($to === self::CURRENCY) {
            return $amount;
        }
        return $amount * $this->getExchangeRate($to);
    }

    private function getExchangeRate(string $currency): float
    {
        GetExchangeRatesJob::dispatch();
        return (new Collection(Cache::get('rates')))->filter(function ($rate) use ($currency) {
            return $rate->getCurrency() === $currency;
        })->first()->getRate();
    }
}
