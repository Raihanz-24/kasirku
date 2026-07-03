<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_cost')->default(0)->after('unit_price');
            $table->unsignedBigInteger('total_cost')->default(0)->after('subtotal');
            $table->bigInteger('gross_profit')->default(0)->after('total_cost');
        });

        DB::table('sale_items')
            ->orderBy('id')
            ->chunkById(200, function ($items): void {
                $costs = DB::table('products')
                    ->whereIn('id', $items->pluck('product_id'))
                    ->pluck('cost_price', 'id');

                foreach ($items as $item) {
                    $unitCost = (int) ($costs[$item->product_id] ?? 0);
                    $totalCost = $unitCost * (int) $item->quantity;

                    DB::table('sale_items')
                        ->where('id', $item->id)
                        ->update([
                            'unit_cost' => $unitCost,
                            'total_cost' => $totalCost,
                            'gross_profit' => (int) $item->subtotal - $totalCost,
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropColumn(['unit_cost', 'total_cost', 'gross_profit']);
        });
    }
};
