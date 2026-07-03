<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('quantity');
            $table->unsignedBigInteger('cost_price')->nullable();
            $table->unsignedBigInteger('selling_price')->nullable();
            $table->unsignedBigInteger('stock_before');
            $table->unsignedBigInteger('stock_after');
            $table->timestamp('occurred_at')->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'occurred_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_ins');
    }
};
