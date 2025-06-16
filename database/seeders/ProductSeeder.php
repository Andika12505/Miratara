<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import DB Facade
use Illuminate\Support\Str;       // Import Str Facade untuk slug

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Laptop Gaming Beast',
                'slug' => Str::slug('Laptop Gaming Beast'), // Generate slug dari nama
                'description' => 'Laptop powerful untuk gaming dan produktivitas tinggi. Dilengkapi RTX 4090.',
                'image_url_1' => 'https://via.placeholder.com/600x400/FF0000/FFFFFF?text=Laptop+Gaming+1', // URL gambar dummy
                'image_url_2' => 'https://via.placeholder.com/600x400/0000FF/FFFFFF?text=Laptop+Gaming+2', // URL gambar dummy
                'price' => 25000000.00, // Contoh harga dalam Rupiah
                'discount_price' => 22000000.00, // Contoh harga diskon
                'category' => 'Electronics',
                'stock' => 50,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Smartphone Flagship 2025',
                'slug' => Str::slug('Smartphone Flagship 2025'),
                'description' => 'Smartphone canggih dengan kamera mutakhir dan performa tak tertandingi.',
                'image_url_1' => 'https://via.placeholder.com/600x400/00FF00/000000?text=Smartphone+1',
                'image_url_2' => 'https://via.placeholder.com/600x400/FFFF00/000000?text=Smartphone+2',
                'price' => 12500000.00,
                'discount_price' => null, // Tidak ada diskon
                'category' => 'Mobile Phones',
                'stock' => 120,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mechanical Keyboard RGB',
                'slug' => Str::slug('Mechanical Keyboard RGB'),
                'description' => 'Keyboard mekanik dengan switch tactile dan pencahayaan RGB customizable.',
                'image_url_1' => 'https://via.placeholder.com/600x400/FFD700/000000?text=Keyboard+1',
                'image_url_2' => 'https://via.placeholder.com/600x400/800080/FFFFFF?text=Keyboard+2',
                'price' => 1500000.00,
                'discount_price' => 1200000.00,
                'category' => 'Accessories',
                'stock' => 200,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Smartwatch Sport Edition',
                'slug' => Str::slug('Smartwatch Sport Edition'),
                'description' => 'Jam tangan pintar untuk gaya hidup aktif, dengan fitur monitoring kesehatan.',
                'image_url_1' => 'https://via.placeholder.com/600x400/ADD8E6/000000?text=Smartwatch+1',
                'image_url_2' => 'https://via.placeholder.com/600x400/90EE90/000000?text=Smartwatch+2',
                'price' => 3000000.00,
                'discount_price' => null,
                'category' => 'Wearables',
                'stock' => 80,
                'is_active' => false, // Contoh produk tidak aktif
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}