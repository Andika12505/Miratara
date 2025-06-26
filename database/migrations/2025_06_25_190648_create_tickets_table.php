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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User yang membuat tiket
            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null'); // Admin/agen yang ditugaskan
            $table->foreignId('category_id')->nullable()->constrained('ticket_categories')->onDelete('set null'); // Kategori tiket
            $table->string('subject');
            $table->text('description');
            $table->string('status')->default('open'); // open, pending, closed
            $table->string('priority')->default('medium'); // low, medium, high
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
