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
        Schema::create('cs_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('cs_articles')->onDelete('cascade'); // Self-referencing FK
            $table->string('question');
            $table->text('answer')->nullable(); // Jawaban bisa kosong jika ini hanya node percabangan
            $table->unsignedSmallInteger('order')->default(0); // Untuk urutan tampilan pilihan
            $table->boolean('is_active')->default(true); // Untuk mengaktifkan/nonaktifkan artikel
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cs_articles');
    }
};
