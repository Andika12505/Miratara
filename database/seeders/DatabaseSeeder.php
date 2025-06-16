<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder-seeder yang ingin kamu jalankan
        $this->call([
            UserSeeder::class,
            ProductSeeder::class,
            // Tambahkan seeder lain di sini jika kamu membuatnya nanti
        ]);
    }
}