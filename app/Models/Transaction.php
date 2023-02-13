<?php

namespace App\Models;

use App\Events\TransactionCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use function auth;

class Transaction extends Model
{
    use Notifiable, HasFactory;

    const CREATED_AT = 'time';
    const UPDATED_AT = null;

    public $timestamps = ["created_at"];

    protected $dispatchesEvents = [
        'created' => TransactionCreated::class,
    ];

    protected $fillable = [
        'outgoing_amount',
        'incoming_amount',
        'from_account_id',
        'to_account_id',
    ];

    public function fromAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'from_account_id', 'id');
    }

    public function toAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'to_account_id', 'id');
    }

    public function getAmountConvertedFormattedAttribute(): string
    {
        $type = $this->getTypeAttribute();
        if ($type === 'Incoming' || $type === 'Buying') {
            $formatter = (new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::CURRENCY));
            if ($this->fromAccount()->value('type') === 'crypto') {
                $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 8);
            }
            return $formatter->formatCurrency(
                $this->outgoing_amount,
                Account::where('id', $this->from_account_id)->value('currency')
            );
        } else {
            $formatter = (new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::CURRENCY));
            if ($this->toAccount()->value('type') === 'crypto') {
                $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 8);
            }
            return $formatter->formatCurrency(
                $this->incoming_amount,
                Account::where('id', $this->to_account_id)->value('currency')
            );
        }
    }

    public function getAmountFormattedAttribute(): string
    {
        $type = $this->getTypeAttribute();
        if ($type === 'Incoming' || $type === 'Buying') {
            $formatter = (new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::CURRENCY));
            if ($this->toAccount()->value('type') === 'crypto') {
                $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 8);
            }
            return $formatter->formatCurrency(
                $this->incoming_amount,
                Account::where('id', $this->to_account_id)->value('currency')
            );
        } else {
            $formatter = (new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::CURRENCY));
            if ($this->fromAccount()->value('type') === 'crypto') {
                $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 8);
            }
            return $formatter->formatCurrency(
                $this->outgoing_amount,
                Account::where('id', $this->from_account_id)->value('currency')
            );
        }
    }

    public function getTypeAttribute(): string
    {
        if (!isset($this->incoming_amount)) {
            return 'FAILED';
        }
        $incomingAccount = Account::where('id', $this->to_account_id)->get();
        $outgoingAccount = Account::where('id', $this->from_account_id)->get();
        $incoming = $incomingAccount->contains('user_id', auth()->user()->id);
        $outgoing = $outgoingAccount->contains('user_id', auth()->user()->id);

        if ($incoming && $outgoing) {
            if (
                $incomingAccount->contains('type', 'regular') &&
                $outgoingAccount->contains('type', 'crypto')
            ) {
                return 'Selling';
            } elseif (
                $incomingAccount->contains('type', 'crypto') &&
                $outgoingAccount->contains('type', 'regular')
            ) {
                return 'Buying';
            }
            return 'Transferring';
        } elseif ($incoming) {
            return 'Incoming';
        } elseif ($outgoing) {
            return 'Outgoing';
        }
        return 'Unknown';
    }

    public function getTimeFormattedAttribute(): string
    {
        return auth()->user()->timezone
            ? $this->time
                ->setTimezone(auth()->user()->timezone)
                ->format('d/m/Y G:i')
            : $this->time
                ->format('d/m/Y G:i');
    }

    public function getFromAccountNameAttribute(): string
    {
        $account = Account::where('id', $this->from_account_id);
        return $account->value('user_id') === auth()->user()->id
            ? $account->value('label') ?? $account->value('number')
            : $account->value('number');
    }

    public function getToAccountNameAttribute(): string
    {
        $account = Account::where('id', $this->to_account_id);
        return $account->value('user_id') === auth()->user()->id
            ? $account->value('label') ?? $account->value('number')
            : $account->value('number');
    }

    public function getFromUserNameAttribute(): string
    {
        return User::where(
            'id',
            Account::where('id', $this->from_account_id)->value('user_id')
        )->value('name');
    }

    public function getToUserNameAttribute(): string
    {
        return User::where(
            'id',
            Account::where('id', $this->to_account_id)->value('user_id')
        )->value('name');
    }

    public function getFromCurrencyAttribute(): string
    {
        return Account::where('id', $this->from_account_id)->value('currency');
    }

    public function getToCurrencyAttribute(): string
    {
        return Account::where('id', $this->to_account_id)->value('currency');
    }
}
