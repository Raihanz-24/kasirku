<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity_in',
        'quantity_out',
        'stock_before',
        'stock_after',
        'reference_type',
        'reference_id',
        'occurred_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity_in' => 'integer',
            'quantity_out' => 'integer',
            'stock_before' => 'integer',
            'stock_after' => 'integer',
            'occurred_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
