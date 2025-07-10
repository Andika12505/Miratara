<?php
// database/migrations/2025_07_10_055752_create_sizes_table.php  (Note: timestamp BEFORE product_sizes)

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
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 10); // e.g., '36', '38', '40'
            $table->string('display_name', 20)->nullable(); // e.g., '36 (XS)', '38 (S)' - optional display format
            $table->integer('sort_order')->default(0); // For ordering sizes (36=1, 38=2, etc.)
            $table->boolean('is_active')->default(true); // Enable/disable sizes
            $table->timestamps();
            
            // Ensure size names are unique
            $table->unique('name');
            
            // Index for sorting
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sizes');
    }
};