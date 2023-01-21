<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use function auth;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'number',
        'label',
        'currency',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function getBalanceFormattedAttribute(): string
    {
        $formatter = (new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::CURRENCY));
        if ($this->type !== 'regular') {
            $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 8);
        }
        return $formatter->formatCurrency($this->balance, $this->currency);
    }

    public function formatTimestamp(Carbon $time): string
    {
        $timezone = auth()->user()->timezone;
        if ($timezone) {
            return date('d/m/Y G:i', strtotime($time->timezone($timezone)));
        }
        return date('d/m/Y G:i', strtotime($time));
    }
}
