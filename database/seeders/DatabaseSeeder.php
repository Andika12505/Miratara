<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan ini di-import
use Illuminate\Support\Facades\Hash; // Pastikan ini di-import

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- Buat user admin pertama (Wajib ada) ---
        User::create([
            'full_name' => 'Administrator Miratara',
            'username' => 'admin',
            'email' => 'admin@miratara.com',
            'phone' => '081122334455',
            'password' => Hash::make('password'), // Password untuk login: 'password'
            'is_admin' => true, // <-- Pastikan ini TRUE
        ]);

        // --- Buat beberapa user dummy lainnya (akan ditampilkan di Kelola User) ---
        // Ini akan menggunakan UserFactory yang sudah Anda modifikasi (full_name, username, is_admin=false default)
        User::factory(10)->create(); // Membuat 10 user dummy
    }
}