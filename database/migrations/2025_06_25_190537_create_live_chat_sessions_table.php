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
        Schema::create('live_chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Pelanggan yang memulai chat
            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null'); // Admin/agen yang ditugaskan
            $table->string('status')->default('waiting'); // waiting, in_progress, closed
            $table->timestamp('started_at')->useCurrent(); // Waktu chat dimulai
            $table->timestamp('ended_at')->nullable(); // Waktu chat berakhir
            $table->text('initial_question')->nullable(); // Pertanyaan awal dari user
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_chat_sessions');
    }
};
