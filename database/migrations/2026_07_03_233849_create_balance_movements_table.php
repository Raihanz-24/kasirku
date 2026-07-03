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
        Schema::create('balance_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 30)->index();
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('balance_before');
            $table->unsignedBigInteger('balance_after');
            $table->nullableMorphs('source');
            $table->string('description')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->timestamps();

            $table->index(['type', 'occurred_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_movements');
    }
};
