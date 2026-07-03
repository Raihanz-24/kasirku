<?php

namespace App\Actions\Sales;

use App\Models\BalanceMovement;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\StoreBalance;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateSaleAction
{
    /**
     * @param  array{
     *     items:array<int,array{product_id:int,quantity:int|float|string}>,
     *     occurred_at?:mixed,
     *     notes?:string|null
     * }  $data
     */
    public function execute(array $data, ?User $user = null): Sale
    {
        $items = $this->normalizeItems($data['items'] ?? []);

        if ($items->isEmpty()) {
            throw ValidationException::withMessages([
                'items' => 'Keranjang penjualan masih kosong.',
            ]);
        }

        return DB::transaction(function () use ($data, $items, $user): Sale {
            $occurredAt = $data['occurred_at'] ?? now();

            $products = Product::query()
                ->whereIn('id', $items->pluck('product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $totalAmount = 0;
            $preparedItems = [];

            foreach ($items as $item) {
                /** @var Product|null $product */
                $product = $products->get($item['product_id']);

                if (! $product) {
                    throw ValidationException::withMessages([
                        'items' => 'Ada produk yang tidak ditemukan.',
                    ]);
                }

                if (! $product->is_active) {
                    throw ValidationException::withMessages([
                        'items' => "Produk {$product->name} sedang nonaktif.",
                    ]);
                }

                $quantity = $this->normalizeQuantity($item['quantity']);
                $stockBefore = $this->normalizeQuantity($product->current_stock);

                if ($quantity > $stockBefore) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->name} tidak cukup. Stok tersedia {$product->current_stock} {$product->unit}.",
                    ]);
                }

                $stockAfter = $this->normalizeQuantity($stockBefore - $quantity);
                $unitPrice = (int) $product->selling_price;
                $unitCost = (int) $product->cost_price;
                $subtotal = $quantity * $unitPrice;
                $totalCost = $quantity * $unitCost;
                $totalAmount += $subtotal;

                $preparedItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'unit_cost' => $unitCost,
                    'subtotal' => $subtotal,
                    'total_cost' => $totalCost,
                    'gross_profit' => $subtotal - $totalCost,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                ];
            }

            $sale = Sale::query()->create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'user_id' => $user?->id,
                'total_amount' => $totalAmount,
                'occurred_at' => $occurredAt,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($preparedItems as $preparedItem) {
                /** @var Product $product */
                $product = $preparedItem['product'];

                $sale->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'quantity' => $preparedItem['quantity'],
                    'unit_price' => $preparedItem['unit_price'],
                    'unit_cost' => $preparedItem['unit_cost'],
                    'subtotal' => $preparedItem['subtotal'],
                    'total_cost' => $preparedItem['total_cost'],
                    'gross_profit' => $preparedItem['gross_profit'],
                    'stock_before' => $preparedItem['stock_before'],
                    'stock_after' => $preparedItem['stock_after'],
                ]);

                $product->forceFill([
                    'current_stock' => $preparedItem['stock_after'],
                ])->save();

                StockMovement::query()->create([
                    'product_id' => $product->id,
                    'user_id' => $user?->id,
                    'type' => 'sale',
                    'quantity_in' => 0,
                    'quantity_out' => $preparedItem['quantity'],
                    'stock_before' => $preparedItem['stock_before'],
                    'stock_after' => $preparedItem['stock_after'],
                    'reference_type' => $sale::class,
                    'reference_id' => $sale->id,
                    'occurred_at' => $occurredAt,
                    'notes' => $data['notes'] ?? null,
                ]);
            }

            $balance = StoreBalance::query()
                ->whereKey(1)
                ->lockForUpdate()
                ->first();

            if (! $balance) {
                $balance = StoreBalance::query()->forceCreate([
                    'id' => 1,
                    'current_balance' => 0,
                ]);
            }

            $balanceBefore = (int) $balance->current_balance;
            $balanceAfter = $balanceBefore + $totalAmount;

            $balance->forceFill([
                'current_balance' => $balanceAfter,
            ])->save();

            BalanceMovement::query()->create([
                'user_id' => $user?->id,
                'type' => 'sale',
                'amount' => $totalAmount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'source_type' => $sale::class,
                'source_id' => $sale->id,
                'description' => "Penjualan {$sale->invoice_number}",
                'occurred_at' => $occurredAt,
            ]);

            return $sale->load('items.product', 'user');
        });
    }

    /**
     * @param  array<int,array{product_id:int,quantity:int|float|string}>  $items
     * @return Collection<int,array{product_id:int,quantity:int}>
     */
    private function normalizeItems(array $items): Collection
    {
        return collect($items)
            ->filter(fn (array $item): bool => filled($item['product_id'] ?? null))
            ->groupBy('product_id')
            ->map(fn (Collection $rows, int|string $productId): array => [
                'product_id' => (int) $productId,
                'quantity' => $this->normalizeQuantity($rows->sum(fn (array $row): int => $this->normalizeQuantity($row['quantity'] ?? 0))),
            ])
            ->filter(fn (array $item): bool => $item['quantity'] > 0)
            ->values();
    }

    private function normalizeQuantity(int|float|string|null $quantity): int
    {
        return max(0, (int) $quantity);
    }

    private function generateInvoiceNumber(): string
    {
        do {
            $invoiceNumber = 'PJL-'.now()->format('Ymd-His').'-'.str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Sale::query()->where('invoice_number', $invoiceNumber)->exists());

        return $invoiceNumber;
    }
}
