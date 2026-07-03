<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'product_name',
        'sku',
        'quantity',
        'unit_price',
        'unit_cost',
        'subtotal',
        'total_cost',
        'gross_profit',
        'stock_before',
        'stock_after',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'integer',
            'unit_cost' => 'integer',
            'subtotal' => 'integer',
            'total_cost' => 'integer',
            'gross_profit' => 'integer',
            'stock_before' => 'integer',
            'stock_after' => 'integer',
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
