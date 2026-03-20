<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity', 10, 2);
            $table->string('unit', 50);
            $table->decimal('price_per_unit', 10, 2);
            $table->date('available_from');
            $table->enum('status', ['available', 'reserved', 'exhausted'])->default('available');
            $table->string('photo_url', 500)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('farm_id');
            $table->index('product_id');
            $table->index('status');
            $table->index('available_from');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};