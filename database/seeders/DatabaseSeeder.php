<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Pastikan Anda memanggil seeder user admin di sini jika Anda ingin ada user admin setelah fresh migrate
        // Jika user admin sudah ada, Anda bisa komentari baris ini atau hapus.
        \App\Models\User::factory()->create([
            'full_name' => 'Admin Utama',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'), // Ganti 'password' dengan password aman
            'is_admin' => 1,
        ]);

        // --- PASTIKAN KEDUA BARIS INI ADA DAN TIDAK DIKOMENTARI ---
        $this->call([
            CsArticleSeeder::class,
            TicketCategorySeeder::class,
            // NEW: Size system seeders
            SizesTableSeeder::class,
            SampleProductSizesSeeder::class,
        ]);
    }
}