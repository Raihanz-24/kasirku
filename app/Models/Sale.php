<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number',
        'user_id',
        'total_amount',
        'occurred_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'integer',
            'occurred_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function balanceMovement(): MorphOne
    {
        return $this->morphOne(BalanceMovement::class, 'source');
    }
}
