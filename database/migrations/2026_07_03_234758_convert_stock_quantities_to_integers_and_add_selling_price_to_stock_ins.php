<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('stock_ins', 'selling_price')) {
            Schema::table('stock_ins', function (Blueprint $table) {
                $table->unsignedBigInteger('selling_price')->nullable()->after('cost_price');
            });
        }

        DB::statement('ALTER TABLE products MODIFY current_stock BIGINT UNSIGNED NOT NULL DEFAULT 0, MODIFY minimum_stock BIGINT UNSIGNED NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE stock_ins MODIFY quantity BIGINT UNSIGNED NOT NULL, MODIFY stock_before BIGINT UNSIGNED NOT NULL, MODIFY stock_after BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE stock_movements MODIFY quantity_in BIGINT UNSIGNED NOT NULL DEFAULT 0, MODIFY quantity_out BIGINT UNSIGNED NOT NULL DEFAULT 0, MODIFY stock_before BIGINT UNSIGNED NOT NULL, MODIFY stock_after BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE sale_items MODIFY quantity BIGINT UNSIGNED NOT NULL, MODIFY stock_before BIGINT UNSIGNED NOT NULL, MODIFY stock_after BIGINT UNSIGNED NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('stock_ins', 'selling_price')) {
            Schema::table('stock_ins', function (Blueprint $table) {
                $table->dropColumn('selling_price');
            });
        }

        DB::statement('ALTER TABLE products MODIFY current_stock DECIMAL(12, 3) NOT NULL DEFAULT 0, MODIFY minimum_stock DECIMAL(12, 3) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE stock_ins MODIFY quantity DECIMAL(12, 3) NOT NULL, MODIFY stock_before DECIMAL(12, 3) NOT NULL, MODIFY stock_after DECIMAL(12, 3) NOT NULL');
        DB::statement('ALTER TABLE stock_movements MODIFY quantity_in DECIMAL(12, 3) NOT NULL DEFAULT 0, MODIFY quantity_out DECIMAL(12, 3) NOT NULL DEFAULT 0, MODIFY stock_before DECIMAL(12, 3) NOT NULL, MODIFY stock_after DECIMAL(12, 3) NOT NULL');
        DB::statement('ALTER TABLE sale_items MODIFY quantity DECIMAL(12, 3) NOT NULL, MODIFY stock_before DECIMAL(12, 3) NOT NULL, MODIFY stock_after DECIMAL(12, 3) NOT NULL');
    }
};
