<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('products')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->each(function (string $category): void {
                DB::table('categories')->updateOrInsert(
                    ['name' => Str::title($category)],
                    [
                        'slug' => Str::slug($category),
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                );
            });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable()
                ->after('barcode')
                ->constrained()
                ->nullOnDelete();
        });

        DB::table('products')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->orderBy('id')
            ->eachById(function (object $product): void {
                $categoryId = DB::table('categories')
                    ->where('name', Str::title($product->category))
                    ->value('id');

                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['category_id' => $categoryId]);
            });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->nullable()->after('category_id')->index();
        });

        DB::table('products')
            ->whereNotNull('category_id')
            ->orderBy('id')
            ->eachById(function (object $product): void {
                $category = DB::table('categories')
                    ->where('id', $product->category_id)
                    ->value('name');

                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['category' => $category]);
            });

        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};
