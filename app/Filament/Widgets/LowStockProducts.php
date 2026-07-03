<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Collection;

class LowStockProducts extends Widget
{
    protected string $view = 'filament.widgets.low-stock-products';

    protected static bool $isLazy = false;

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = ['md' => 1];

    /** @return Collection<int, Product> */
    public function getProducts(): Collection
    {
        return Product::query()
            ->where('is_active', true)
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->orderBy('current_stock')
            ->limit(6)
            ->get();
    }

    public function getProductUrl(int $productId): string
    {
        return ProductResource::getUrl('view', ['record' => $productId]);
    }
}
