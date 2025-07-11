<?php
// database/migrations/2025_07_10_000003_create_stock_alerts_table.php

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
        Schema::create('stock_alerts', function (Blueprint $table) {
            $table->id();
            
            // Product reference
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Alert details
            $table->enum('type', ['low_stock', 'out_of_stock', 'overstock', 'expiring_soon']); // Alert type
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium'); // Alert priority
            $table->enum('status', ['active', 'acknowledged', 'resolved'])->default('active'); // Alert status
            
            // Alert thresholds and values
            $table->integer('current_stock'); // Current stock when alert was triggered
            $table->integer('threshold_value')->nullable(); // Threshold that triggered alert
            $table->text('message'); // Alert message
            
            // User interaction
            $table->foreignId('acknowledged_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('acknowledged_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();
            
            // Additional data
            $table->json('metadata')->nullable(); // Additional alert data
            $table->date('expires_at')->nullable(); // For expiring product alerts
            
            $table->timestamps();
            
            // Indexes
            $table->index(['product_id', 'status']);
            $table->index(['type', 'priority']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_alerts');
    }
};