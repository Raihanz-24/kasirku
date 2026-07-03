<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Withdrawal extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'purpose',
        'notes',
        'balance_before',
        'balance_after',
        'occurred_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'balance_before' => 'integer',
            'balance_after' => 'integer',
            'occurred_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function balanceMovement(): MorphOne
    {
        return $this->morphOne(BalanceMovement::class, 'source');
    }
}
