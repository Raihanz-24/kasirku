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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->string('barcode')->nullable()->unique();
            $table->string('category')->nullable()->index();
            $table->string('unit', 30)->default('pcs');
            $table->unsignedBigInteger('cost_price')->default(0);
            $table->unsignedBigInteger('selling_price')->default(0);
            $table->unsignedBigInteger('current_stock')->default(0);
            $table->unsignedBigInteger('minimum_stock')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['name', 'sku', 'barcode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
