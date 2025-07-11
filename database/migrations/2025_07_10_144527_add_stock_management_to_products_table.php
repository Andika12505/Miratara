<?php
// database/migrations/2025_07_10_000001_add_stock_management_to_products_table.php

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
        Schema::table('products', function (Blueprint $table) {
            // Stock management columns
            $table->integer('min_stock')->default(5)->after('stock'); // Minimum stock alert
            $table->integer('max_stock')->default(100)->after('min_stock'); // Maximum stock capacity
            $table->integer('reserved_stock')->default(0)->after('max_stock'); // Stock reserved for pending orders
            $table->integer('available_stock')->storedAs('stock - reserved_stock')->after('reserved_stock'); // Available stock (calculated)
            
            // Stock status and tracking
            $table->enum('stock_status', ['in_stock', 'low_stock', 'out_of_stock'])->default('in_stock')->after('available_stock');
            $table->timestamp('last_stock_update')->nullable()->after('stock_status');
            $table->string('stock_unit', 50)->default('pcs')->after('last_stock_update'); // Unit measurement (pcs, kg, m, etc.)
            
            // Supplier and cost tracking
            $table->decimal('cost_price', 10, 2)->nullable()->after('stock_unit'); // Cost price for profit calculation
            $table->string('supplier')->nullable()->after('cost_price'); // Supplier name
            $table->string('sku')->nullable()->unique()->after('supplier'); // Stock Keeping Unit
            
            // Location and batch tracking
            $table->string('location')->nullable()->after('sku'); // Warehouse location
            $table->string('batch_number')->nullable()->after('location'); // Batch number for tracking
            $table->date('expiry_date')->nullable()->after('batch_number'); // For products with expiry
            
            // Indexes for better performance
            $table->index(['stock_status']);
            $table->index(['min_stock']);
            $table->index(['sku']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'min_stock', 'max_stock', 'reserved_stock', 'available_stock',
                'stock_status', 'last_stock_update', 'stock_unit',
                'cost_price', 'supplier', 'sku', 'location', 'batch_number', 'expiry_date'
            ]);
        });
    }
};