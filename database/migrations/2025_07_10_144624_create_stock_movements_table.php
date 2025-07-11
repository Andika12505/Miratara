<?php
// database/migrations/2025_07_10_000002_create_stock_movements_table.php

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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            
            // Product and user references
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who made the change
            
            // Movement details
            $table->enum('type', ['in', 'out', 'adjustment', 'reserved', 'released']); // Type of movement
            $table->integer('quantity'); // Quantity moved (positive for in, negative for out)
            $table->integer('stock_before'); // Stock before the movement
            $table->integer('stock_after'); // Stock after the movement
            
            // Movement context
            $table->enum('reason', [
                'purchase', 'sale', 'return', 'damaged', 'expired', 
                'adjustment', 'transfer', 'reservation', 'cancellation'
            ])->default('adjustment');
            
            // Additional details
            $table->string('reference_type')->nullable(); // Related model type (Order, Return, etc.)
            $table->unsignedBigInteger('reference_id')->nullable(); // Related model ID
            $table->text('notes')->nullable(); // Additional notes
            $table->decimal('unit_cost', 10, 2)->nullable(); // Cost per unit at time of movement
            $table->decimal('total_cost', 10, 2)->nullable(); // Total cost of movement
            
            // Batch and location tracking
            $table->string('batch_number')->nullable();
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['product_id', 'created_at']);
            $table->index(['type', 'reason']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};