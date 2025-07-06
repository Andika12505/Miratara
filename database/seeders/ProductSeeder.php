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
        // Ensure 'Fashion' category exists
        $category = Category::firstOrCreate(
            ['name' => 'Fashion'],
            ['slug' => 'fashion']
        );

        // Truncate existing products to start fresh
        Product::truncate();

        // Sample products with updated English metadata
        $products = [
            [
                'name' => 'Suvi Cotton Midi Dress',
                'slug' => 'suvi-cotton-midi-dress',
                'description' => 'Beautiful cotton midi dress perfect for any occasion',
                'price' => 500000,
                'stock' => 10,
                'category_id' => $category->id,
                'is_active' => 1,
                'image' => 'a1.png',
                'metadata' => json_encode([
                    "vibe_attributes" => [
                        "occasion" => ["casual_day", "office"],
                        "style" => ["casual", "minimalist"],
                        "material" => ["cotton"],
                        "color_tone" => ["pastel", "earthy"],
                        "fit" => ["regular", "loose"],
                        "pattern" => ["solid"],
                        "neckline" => ["crew_neck"],
                        "sleeve_length" => ["short_sleeve"]
                    ],
                    "general_tags" => ["summer"],
                    "care_instructions" => "Machine wash",
                    "origin" => "Indonesia"
                ])
            ],
            [
                'name' => 'Norma Maxi Dress',
                'slug' => 'norma-maxi-dress',
                'description' => 'Elegant maxi dress for special events',
                'price' => 600000,
                'stock' => 8,
                'category_id' => $category->id,
                'is_active' => 1,
                'image' => 'a3.png',
                'metadata' => json_encode([
                    "vibe_attributes" => [
                        "occasion" => ["party", "formal", "holiday"],
                        "style" => ["elegant"],
                        "material" => ["silk"],
                        "color_tone" => ["dark"],
                        "fit" => ["regular"],
                        "pattern" => ["solid"],
                        "neckline" => ["v_neck"],
                        "sleeve_length" => ["sleeveless"]
                    ],
                    "general_tags" => [],
                    "care_instructions" => "Dry clean only",
                    "origin" => "Indonesia"
                ])
            ],
            [
                'name' => 'Chessie Heritage Cotton Maxi Dress',
                'slug' => 'chessie-heritage-cotton-maxi-dress',
                'description' => 'Heritage style cotton maxi dress',
                'price' => 650000,
                'stock' => 5,
                'category_id' => $category->id,
                'is_active' => 1,
                'image' => 'a5.png',
                'metadata' => json_encode([
                    "vibe_attributes" => [
                        "occasion" => ["loungewear", "beach"],
                        "style" => ["bohemian", "casual"],
                        "material" => ["cotton"],
                        "color_tone" => ["earthy", "pastel"],
                        "fit" => ["loose"],
                        "pattern" => ["floral"],
                        "neckline" => ["crew_neck"],
                        "sleeve_length" => ["long_sleeve"]
                    ],
                    "general_tags" => ["eco_friendly"],
                    "care_instructions" => "Hand wash",
                    "origin" => "Indonesia"
                ])
            ],
            [
                'name' => 'Rialto Fragrance Print Maxi Dress',
                'slug' => 'rialto-fragrance-print-maxi-dress',
                'description' => 'Fragrant print maxi dress',
                'price' => 950000,
                'stock' => 12,
                'category_id' => $category->id,
                'is_active' => 1,
                'image' => 'a7.png',
                'metadata' => json_encode([
                    "vibe_attributes" => [
                        "occasion" => ["party", "holiday"],
                        "style" => ["elegant", "bohemian"],
                        "material" => ["silk"],
                        "color_tone" => ["bright"],
                        "fit" => ["regular"],
                        "pattern" => ["animal_print"],
                        "neckline" => ["v_neck"],
                        "sleeve_length" => ["sleeveless"]
                    ],
                    "general_tags" => ["limited_edition"],
                    "care_instructions" => "Dry clean only",
                    "origin" => "Italy"
                ])
            ],
            [
                'name' => 'Ryan Catalina Lace Maxi Dress',
                'slug' => 'ryan-catalina-lace-maxi-dress',
                'description' => 'Lace maxi dress with Catalina style',
                'price' => 950000,
                'stock' => 6,
                'category_id' => $category->id,
                'is_active' => 1,
                'image' => 'a9.png',
                'metadata' => json_encode([
                    "vibe_attributes" => [
                        "occasion" => ["party", "formal"],
                        "style" => ["elegant", "glamour"],
                        "material" => ["polyester", "silk"],
                        "color_tone" => ["dark"],
                        "fit" => ["slim_fit"],
                        "pattern" => ["solid"],
                        "neckline" => ["turtleneck"],
                        "sleeve_length" => ["long_sleeve"]
                    ],
                    "general_tags" => [],
                    "care_instructions" => "Hand wash cold",
                    "origin" => "China"
                ])
            ],
            [
                'name' => 'Rialto Pastel Maxi Dress',
                'slug' => 'rialto-pastel-maxi-dress',
                'description' => 'Pastel colored maxi dress',
                'price' => 900000,
                'stock' => 15,
                'category_id' => $category->id,
                'is_active' => 1,
                'image' => 'a11.png',
                'metadata' => json_encode([
                    "vibe_attributes" => [
                        "occasion" => ["loungewear", "holiday"],
                        "style" => ["casual", "bohemian"],
                        "material" => ["cotton", "linen"],
                        "color_tone" => ["pastel"],
                        "fit" => ["loose"],
                        "pattern" => ["solid"],
                        "neckline" => ["crew_neck"],
                        "sleeve_length" => ["sleeveless"]
                    ],
                    "general_tags" => ["summer"],
                    "care_instructions" => "Machine wash",
                    "origin" => "Indonesia"
                ])
            ],
            [
                'name' => 'Classic Denim Jacket',
                'slug' => 'classic-denim-jacket',
                'description' => 'A timeless denim jacket for all seasons',
                'price' => 450000,
                'stock' => 20,
                'category_id' => $category->id,
                'is_active' => 1,
                'image' => 'a13.png',
                'metadata' => json_encode([
                    "vibe_attributes" => [
                        "occasion" => ["casual_day", "office", "everyday"],
                        "style" => ["casual", "sporty"],
                        "material" => ["denim"],
                        "color_tone" => ["dark"],
                        "fit" => ["regular"],
                        "pattern" => ["solid"],
                        "neckline" => ["shirt_collar"],
                        "sleeve_length" => ["long_sleeve"]
                    ],
                    "general_tags" => ["oversized"],
                    "care_instructions" => "Machine wash cold",
                    "origin" => "USA"
                ])
            ],
            [
                'name' => 'Elegant Silk Scarf',
                'slug' => 'elegant-silk-scarf',
                'description' => 'Luxurious silk scarf to elevate any outfit',
                'price' => 200000,
                'stock' => 25,
                'category_id' => $category->id,
                'is_active' => 1,
                'image' => 'a15.png',
                'metadata' => json_encode([
                    "vibe_attributes" => [
                        "occasion" => ["party", "office", "formal"],
                        "style" => ["elegant"],
                        "material" => ["silk"],
                        "color_tone" => ["bright", "pastel"],
                        "pattern" => ["geometric"]
                    ],
                    "general_tags" => [],
                    "care_instructions" => "Dry clean only",
                    "origin" => "France"
                ])
            ]
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        $this->command->info('✅ Sample products created successfully!');
        $this->command->info('📊 Total products: ' . Product::count());
    }
}