<?php
// database/seeders/SizesTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            ['name' => '34', 'display_name' => '34 (XXS)', 'sort_order' => 1],
            ['name' => '36', 'display_name' => '36 (XS)', 'sort_order' => 2],
            ['name' => '38', 'display_name' => '38 (S)', 'sort_order' => 3],
            ['name' => '40', 'display_name' => '40 (M)', 'sort_order' => 4],
            ['name' => '42', 'display_name' => '42 (L)', 'sort_order' => 5],
            ['name' => '44', 'display_name' => '44 (XL)', 'sort_order' => 6],
            ['name' => '46', 'display_name' => '46 (XXL)', 'sort_order' => 7],
            ['name' => '48', 'display_name' => '48 (XXXL)', 'sort_order' => 8],
        ];

        foreach ($sizes as $size) {
            DB::table('sizes')->insert([
                'name' => $size['name'],
                'display_name' => $size['display_name'],
                'sort_order' => $size['sort_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}