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

    public function getAmountFormattedAttribute(): string
    {
        $type = $this->getTypeAttribute();
        if ($type === 'Incoming' || $type === 'Selling') {
            return (new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::CURRENCY))
                ->formatCurrency(
                    $this->incoming_amount,
                    Account::where('id', $this->to_account_id)->value('currency')
                );
        } else {
            return (new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::CURRENCY))
                ->formatCurrency(
                    $this->outgoing_amount,
                    Account::where('id', $this->from_account_id)->value('currency')
                );
        }
    }

    public function getTypeAttribute(): string
    {
        $incomingAccount = Account::where('id', $this->to_account_id)->get();
        $outgoingAccount = Account::where('id', $this->from_account_id)->get();
        $incoming = $incomingAccount->contains('user_id', auth()->user()->id);
        $outgoing = $outgoingAccount->contains('user_id', auth()->user()->id);

        if ($incoming && $outgoing) {
            if (
                $incomingAccount->contains('type', 'regular') &&
                $outgoingAccount->contains('type', 'crypto')
            ) {
                return 'Buying';
            } elseif (
                $incomingAccount->contains('type', 'crypto') &&
                $outgoingAccount->contains('type', 'regular')
            ) {
                return 'Selling';
            }
            return 'Internal';
        } elseif ($incoming && !$outgoing) {
            return 'Incoming';
        } elseif (!$incoming && $outgoing) {
            return 'Outgoing';
        }
        return 'Unknown';
    }

    public function getFromAccountNumberAttribute(): string
    {
        return Account::where('id', $this->from_account_id)->value('number');
    }

    public function getToAccountNumberAttribute(): string
    {
        return Account::where('id', $this->to_account_id)->value('number');
    }

    public function getTimestampFormattedAttribute(): string
    {
        if (auth()->user()->timezone) {
            return date('d/m/Y G:i', strtotime($this->time->timezone(auth()->user()->timezone)));
        }
        return date('d/m/Y G:i', strtotime($this->time));
    }
}
