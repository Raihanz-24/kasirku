<?php

namespace App\Actions\Inventory;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RecordStockInAction
{
    /**
     * @param  array{
     *     product_id:int,
     *     quantity:int|float|string,
     *     cost_price?:int|string|null,
     *     selling_price?:int|string|null,
     *     occurred_at?:mixed,
     *     notes?:string|null
     * }  $data
     */
    public function execute(array $data, ?User $user = null): StockIn
    {
        $quantity = $this->normalizeQuantity($data['quantity'] ?? 0);

        if ($quantity <= 0) {
            throw ValidationException::withMessages([
                'quantity' => 'Jumlah barang masuk harus lebih dari 0.',
            ]);
        }

        return DB::transaction(function () use ($data, $quantity, $user): StockIn {
            /** @var Product $product */
            $product = Product::query()
                ->lockForUpdate()
                ->findOrFail($data['product_id']);

            if (! $product->is_active) {
                throw ValidationException::withMessages([
                    'product_id' => 'Produk nonaktif tidak bisa diinput barang masuk.',
                ]);
            }

            $stockBefore = $this->normalizeQuantity($product->current_stock);
            $stockAfter = $this->normalizeQuantity($stockBefore + $quantity);
            $costPrice = filled($data['cost_price'] ?? null) ? (int) $data['cost_price'] : null;
            $sellingPrice = filled($data['selling_price'] ?? null) ? (int) $data['selling_price'] : null;
            $occurredAt = $data['occurred_at'] ?? now();

            $stockIn = StockIn::query()->create([
                'product_id' => $product->id,
                'user_id' => $user?->id,
                'quantity' => $quantity,
                'cost_price' => $costPrice,
                'selling_price' => $sellingPrice,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'occurred_at' => $occurredAt,
                'notes' => $data['notes'] ?? null,
            ]);

            $product->forceFill([
                'current_stock' => $stockAfter,
                ...($costPrice !== null ? ['cost_price' => $costPrice] : []),
                ...($sellingPrice !== null ? ['selling_price' => $sellingPrice] : []),
            ])->save();

            StockMovement::query()->create([
                'product_id' => $product->id,
                'user_id' => $user?->id,
                'type' => 'stock_in',
                'quantity_in' => $quantity,
                'quantity_out' => 0,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reference_type' => $stockIn::class,
                'reference_id' => $stockIn->id,
                'occurred_at' => $occurredAt,
                'notes' => $data['notes'] ?? null,
            ]);

            return $stockIn;
        });
    }

    private function normalizeQuantity(int|float|string|null $quantity): int
    {
        return max(0, (int) $quantity);
    }
}
