<?php
// database/migrations/2025_07_10_061643_create_product_sizes_table.php

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
        Schema::create('product_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('size_id')->constrained()->onDelete('cascade');
            $table->integer('stock')->default(0); // Stock for this specific product-size combination
            $table->boolean('is_available')->default(true); // Can temporarily disable a size for a product
            $table->timestamps();
            
            // Ensure each product-size combination is unique
            $table->unique(['product_id', 'size_id']);
            
            // Indexes for performance
            $table->index('product_id');
            $table->index('size_id');
            $table->index('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sizes');
    }
};