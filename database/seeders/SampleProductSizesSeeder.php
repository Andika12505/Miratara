<?php
// database/seeders/SampleProductSizesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Support\Facades\DB;

class SampleProductSizesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first product (Suvi Cotton Midi Dress)
        $product = Product::first();
        
        if (!$product) {
            $this->command->info('No products found. Please add products first.');
            return;
        }

        // Get all sizes
        $sizes = Size::orderBy('sort_order')->get();
        
        if ($sizes->isEmpty()) {
            $this->command->info('No sizes found. Please run SizesTableSeeder first.');
            return;
        }

        // Add sample sizes to the product with different stock levels
        $sampleSizeData = [
            '36' => ['stock' => 2, 'is_available' => true],
            '38' => ['stock' => 5, 'is_available' => true], 
            '40' => ['stock' => 3, 'is_available' => true],
            '42' => ['stock' => 4, 'is_available' => true],
            '44' => ['stock' => 0, 'is_available' => false], // Out of stock
            '46' => ['stock' => 1, 'is_available' => true],
        ];

        foreach ($sampleSizeData as $sizeName => $data) {
            $size = $sizes->where('name', $sizeName)->first();
            
            if ($size) {
                // Check if this product-size combination already exists
                $exists = DB::table('product_sizes')
                           ->where('product_id', $product->id)
                           ->where('size_id', $size->id)
                           ->exists();

                if (!$exists) {
                    DB::table('product_sizes')->insert([
                        'product_id' => $product->id,
                        'size_id' => $size->id,
                        'stock' => $data['stock'],
                        'is_available' => $data['is_available'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $this->command->info("Added sample sizes to product: {$product->name}");
        
        // Show total stock
        $totalSizeStock = DB::table('product_sizes')
                           ->where('product_id', $product->id)
                           ->sum('stock');
        
        $this->command->info("Total stock across all sizes: {$totalSizeStock}");
        $this->command->info("Original product stock: {$product->stock}");
    }
}