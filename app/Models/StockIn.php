<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class StockIn extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'quantity',
        'cost_price',
        'selling_price',
        'stock_before',
        'stock_after',
        'occurred_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'cost_price' => 'integer',
            'selling_price' => 'integer',
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

    public function stockMovement(): MorphOne
    {
        return $this->morphOne(StockMovement::class, 'reference');
    }
}
