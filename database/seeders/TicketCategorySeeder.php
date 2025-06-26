<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TicketCategory;
use Illuminate\Support\Facades\DB; // Import DB Facade

class TicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the table to avoid duplicate entries on re-seeding
        TicketCategory::truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        TicketCategory::create([
            'name' => 'Dukungan Teknis',
            'description' => 'Masalah terkait penggunaan aplikasi, bug, atau error teknis.',
        ]);

        TicketCategory::create([
            'name' => 'Pertanyaan Pembayaran',
            'description' => 'Pertanyaan atau masalah terkait transaksi dan pembayaran.',
        ]);

        TicketCategory::create([
            'name' => 'Informasi Produk',
            'description' => 'Pertanyaan mendalam tentang spesifikasi atau ketersediaan produk.',
        ]);

        TicketCategory::create([
            'name' => 'Keluhan & Saran',
            'description' => 'Masukan, keluhan, atau saran untuk peningkatan layanan.',
        ]);

        TicketCategory::create([
            'name' => 'Lain-lain',
            'description' => 'Topik yang tidak termasuk dalam kategori di atas.',
        ]);
    }
}
