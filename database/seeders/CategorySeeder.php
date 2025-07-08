<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // <-- Tambahkan ini untuk menggunakan Str::slug()
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama agar tidak duplikat
        DB::table('categories')->delete();

        $categories = [
            'Dresses',
            'Skirts',
            'Tops',
            'Jackets',
            'Accessories',
            'Shoes'
        ];

        // Looping untuk setiap kategori untuk membuat data
        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category,
                'slug' => Str::slug($category), // Membuat slug otomatis, cth: "dresses"
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}