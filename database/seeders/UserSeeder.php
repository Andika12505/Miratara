<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;   // Import DB Facade
use Illuminate\Support\Facades\Hash; // Import Hash Facade
use Illuminate\Support\Str;          // Import Str Facade untuk random string

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Masukkan satu user admin
        DB::table('users')->insert([
            'full_name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'phone' => '081234567890', // Contoh nomor telepon
            'password' => Hash::make('password'), // Enkripsi password 'password'
            'is_admin' => true, // Set sebagai admin
            'remember_token' => Str::random(10), // Random token
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Opsional: Tambahkan beberapa user biasa (non-admin)
        for ($i = 1; $i <= 5; $i++) {
            DB::table('users')->insert([
                'full_name' => 'User Biasa ' . $i,
                'username' => 'user' . $i,
                'email' => 'user' . $i . '@example.com',
                'email_verified_at' => now(),
                'phone' => '087' . rand(100000000, 999999999), // Contoh nomor telepon acak
                'password' => Hash::make('password123'), // Enkripsi password 'password123'
                'is_admin' => false, // Set sebagai user biasa
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}