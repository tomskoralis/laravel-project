<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'code',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUpdatedAtFormattedAttribute(): string
    {
        $updatedAt = Carbon::parse($this->updated_at);
        $timezone = auth()->user()->timezone;
        return $timezone
            ? $updatedAt->timezone($timezone)->format('d/m/Y G:i')
            : $updatedAt->format('d/m/Y G:i');
    }
}
