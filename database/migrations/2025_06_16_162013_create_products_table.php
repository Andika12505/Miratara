// database/migrations/YYYY_MM_DD_HHMMSS_create_products_table.php

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
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Foreign key ke tabel categories
            $table->string('name');
            $table->string('slug')->unique(); // Slug produk, unik
            $table->text('description')->nullable(); // Deskripsi produk, bisa kosong
            $table->string('image')->nullable(); // Path gambar produk, bisa kosong
            $table->decimal('price', 10, 2); // Harga produk (10 digit total, 2 di belakang koma)
            $table->integer('stock')->default(0); // Stok produk, default 0
            $table->json('metadata')->nullable(); // Kolom JSON untuk data fleksibel, bisa kosong
            $table->boolean('is_active')->default(true); // Status produk, default aktif
            $table->timestamps(); // created_at dan updated_at
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