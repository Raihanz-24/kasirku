<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'barcode',
        'category_id',
        'unit',
        'cost_price',
        'selling_price',
        'current_stock',
        'minimum_stock',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'cost_price' => 'integer',
            'selling_price' => 'integer',
            'category_id' => 'integer',
            'current_stock' => 'integer',
            'minimum_stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product): void {
            if (filled($product->sku)) {
                $product->sku = Str::upper($product->sku);

                return;
            }

            $product->sku = static::generateSku();
        });

        static::saving(function (Product $product): void {
            $product->sku = Str::upper((string) $product->sku);
            $product->barcode = blank($product->barcode) ? null : (string) $product->barcode;
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public static function generateSku(): string
    {
        $nextId = ((int) static::withTrashed()->max('id')) + 1;

        do {
            $sku = 'PRD-'.str_pad((string) $nextId, 6, '0', STR_PAD_LEFT);
            $nextId++;
        } while (static::withTrashed()->where('sku', $sku)->exists());

        return $sku;
    }

    public function isLowStock(): bool
    {
        return (int) $this->current_stock <= (int) $this->minimum_stock;
    }
}
