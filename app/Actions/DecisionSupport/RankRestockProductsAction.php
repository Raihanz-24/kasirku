<?php

namespace App\Actions\DecisionSupport;

use App\Models\Product;
use App\Models\SaleItem;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class RankRestockProductsAction
{
    /**
     * @return Collection<int, array{
     *     product: Product,
     *     frequency: int,
     *     usage: int,
     *     remaining_stock: int,
     *     score: float
     * }>
     */
    public function execute(?CarbonInterface $from = null, ?CarbonInterface $to = null, int $limit = 5): Collection
    {
        $from ??= today()->subDays(29)->startOfDay();
        $to ??= today()->endOfDay();

        $products = Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        if ($products->isEmpty()) {
            return collect();
        }

        $salesMetrics = SaleItem::query()
            ->selectRaw('sale_items.product_id, COUNT(DISTINCT sale_items.sale_id) as frequency, SUM(sale_items.quantity) as `usage`')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->whereBetween('sales.occurred_at', [$from, $to])
            ->whereIn('sale_items.product_id', $products->pluck('id'))
            ->groupBy('sale_items.product_id')
            ->get()
            ->keyBy('product_id');

        $rows = $products->map(function (Product $product) use ($salesMetrics): array {
            $metric = $salesMetrics->get($product->id);

            return [
                'product' => $product,
                'frequency' => (int) ($metric?->frequency ?? 0),
                'usage' => (int) ($metric?->usage ?? 0),
                'remaining_stock' => max(0, (int) $product->current_stock),
            ];
        });

        $maxFrequency = max(1, (int) $rows->max('frequency'));
        $maxUsage = max(1, (int) $rows->max('usage'));
        $minimumStockBasis = (int) $rows->min(fn (array $row): int => max(1, $row['remaining_stock']));

        return $rows
            ->map(function (array $row) use ($maxFrequency, $maxUsage, $minimumStockBasis): array {
                $frequencyScore = $row['frequency'] / $maxFrequency;
                $usageScore = $row['usage'] / $maxUsage;
                $stockScore = $minimumStockBasis / max(1, $row['remaining_stock']);

                return [
                    ...$row,
                    'score' => round(($frequencyScore + $usageScore + $stockScore) / 3, 4),
                ];
            })
            ->sortByDesc('score')
            ->take(max(1, $limit))
            ->values();
    }
}
