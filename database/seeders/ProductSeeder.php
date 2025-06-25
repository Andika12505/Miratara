<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada kategori dulu
        $category = Category::first();
        
        if (!$category) {
            $category = Category::create([
                'name' => 'Fashion',
                'description' => 'Fashion Items',
                'slug' => 'fashion'
            ]);
        }

        // Hapus produk lama jika ada
        Product::truncate();

        // Sample products dengan gambar dari public/images
        $products = [
            [
                'name' => 'Suvi Cotton Midi Dress',
                'slug' => 'suvi-cotton-midi-dress',
                'description' => 'Beautiful cotton midi dress perfect for any occasion',
                'price' => 500000,
                'stock' => 10,
                'category_id' => $category->id,
                'is_active' => 1,
                'image' => 'a1.png', // Gambar di public/images/a1.png
                'metadata' => json_encode([])
            ],
            [
                'name' => 'Norma Maxi Dress',
                'slug' => 'norma-maxi-dress',
                'description' => 'Elegant maxi dress for special events',
                'price' => 600000,
                'stock' => 8,
                'category_id' => $category->id,
                'is_active' => 1,
                'image' => 'a3.png', // Gambar di public/images/a3.png
                'metadata' => json_encode([])
            ],
            [
                'name' => 'Chessie Heritage Cotton Maxi Dress',
                'slug' => 'chessie-heritage-cotton-maxi-dress',
                'description' => 'Heritage style cotton maxi dress',
                'price' => 650000,
                'stock' => 5,
                'category_id' => $category->id,
                'is_active' => 1,
                'image' => 'a5.png', // Gambar di public/images/a5.png
                'metadata' => json_encode([])
            ],
            [
                'name' => 'Rialto Fragrance Print Maxi Dress',
                'slug' => 'rialto-fragrance-print-maxi-dress',
                'description' => 'Fragrant print maxi dress',
                'price' => 950000,
                'stock' => 12,
                'category_id' => $category->id,
                'is_active' => 1,
                'image' => 'a7.png', // Gambar di public/images/a7.png
                'metadata' => json_encode([])
            ],
            [
                'name' => 'Ryan Catalina Lace Maxi Dress',
                'slug' => 'ryan-catalina-lace-maxi-dress',
                'description' => 'Lace maxi dress with Catalina style',
                'price' => 950000,
                'stock' => 6,
                'category_id' => $category->id,
                'is_active' => 1,
                'image' => 'a9.png', // Gambar di public/images/a9.png
                'metadata' => json_encode([])
            ],
            [
                'name' => 'Rialto Pastel Maxi Dress',
                'slug' => 'rialto-pastel-maxi-dress',
                'description' => 'Pastel colored maxi dress',
                'price' => 900000,
                'stock' => 15,
                'category_id' => $category->id,
                'is_active' => 1,
                'image' => 'a11.png', // Gambar di public/images/a11.png
                'metadata' => json_encode([])
            ]
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        $this->command->info('✅ Sample products created successfully!');
        $this->command->info('📊 Total products: ' . Product::count());
    }
}