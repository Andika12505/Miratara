<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CsArticle; // Import model CsArticle

class CsArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel kosong sebelum seeding untuk menghindari duplikasi saat run berulang
        CsArticle::truncate();

        // Level 1: Topik Utama (parent_id = NULL)
        $loginTrouble = CsArticle::create([
            'question' => 'Masalah Login / Akun',
            'answer' => null, // Ini adalah node percabangan, jadi tidak ada jawaban langsung
            'order' => 1,
            'is_active' => true,
        ]);

        $orderIssue = CsArticle::create([
            'question' => 'Masalah Pesanan / Pengiriman',
            'answer' => null,
            'order' => 2,
            'is_active' => true,
        ]);

        $paymentIssue = CsArticle::create([
            'question' => 'Masalah Pembayaran',
            'answer' => null,
            'order' => 3,
            'is_active' => true,
        ]);

        $productInquiry = CsArticle::create([
            'question' => 'Pertanyaan Produk',
            'answer' => 'Anda dapat menemukan informasi detail produk di halaman produk masing-masing. Jika ada yang spesifik, mohon sebutkan nama produknya.',
            'order' => 4,
            'is_active' => true,
        ]);

        // Opsi "Chat dengan Admin Langsung" - ini juga bisa berupa CsArticle
        // yang mengarah ke aksi tertentu di frontend.
        $chatAdminOption = CsArticle::create([
            'question' => 'Butuh bantuan lebih lanjut? Chat dengan Admin.',
            'answer' => 'Mohon tunggu sebentar, kami sedang menghubungkan Anda dengan agen dukungan kami. Mohon jelaskan masalah Anda secara singkat.',
            'order' => 5,
            'is_active' => true,
            // Anda bisa menambahkan kolom 'action_type' jika perlu menandai ini sebagai aksi khusus
            // 'action_type' => 'live_chat'
        ]);


        // Level 2: Sub-topik untuk "Masalah Login / Akun"
        CsArticle::create([
            'parent_id' => $loginTrouble->id,
            'question' => 'Lupa Password',
            'answer' => 'Jika Anda lupa password, silakan gunakan fitur "Lupa Password" di halaman login untuk mengatur ulang password Anda. Link: [Link Lupa Password]',
            'order' => 1,
            'is_active' => true,
        ]);

        CsArticle::create([
            'parent_id' => $loginTrouble->id,
            'question' => 'Akun Terkunci / Diblokir',
            'answer' => 'Akun Anda mungkin terkunci karena percobaan login yang gagal berulang kali. Mohon tunggu beberapa saat atau hubungi kami untuk membuka blokir akun Anda.',
            'order' => 2,
            'is_active' => true,
        ]);

        // Level 2: Sub-topik untuk "Masalah Pesanan / Pengiriman"
        CsArticle::create([
            'parent_id' => $orderIssue->id,
            'question' => 'Status Pesanan Belum Berubah',
            'answer' => 'Status pesanan Anda mungkin belum diperbarui. Mohon berikan nomor pesanan Anda agar kami dapat memeriksanya.',
            'order' => 1,
            'is_active' => true,
        ]);

        CsArticle::create([
            'parent_id' => $orderIssue->id,
            'question' => 'Pesanan Tidak Sampai',
            'answer' => 'Kami mohon maaf atas ketidaknyamanan ini. Mohon berikan nomor pesanan dan alamat pengiriman Anda agar kami dapat menindaklanjuti dengan pihak kurir.',
            'order' => 2,
            'is_active' => true,
        ]);

        // Level 2: Sub-topik untuk "Masalah Pembayaran"
        CsArticle::create([
            'parent_id' => $paymentIssue->id,
            'question' => 'Pembayaran Gagal',
            'answer' => 'Pembayaran Anda mungkin gagal karena beberapa alasan. Mohon pastikan saldo Anda cukup atau coba metode pembayaran lain. Jika masalah berlanjut, hubungi bank Anda.',
            'order' => 1,
            'is_active' => true,
        ]);

        CsArticle::create([
            'parent_id' => $paymentIssue->id,
            'question' => 'Double Pembayaran',
            'answer' => 'Jika Anda melakukan pembayaran ganda, mohon berikan bukti transfer dan detail pesanan Anda. Kami akan segera melakukan verifikasi dan pengembalian dana jika diperlukan.',
            'order' => 2,
            'is_active' => true,
        ]);

        // Level 3 (Contoh): Sub-sub-topik
        $blockedAccount = CsArticle::where('question', 'Akun Terkunci / Diblokir')->first();
        if ($blockedAccount) {
            CsArticle::create([
                'parent_id' => $blockedAccount->id,
                'question' => 'Cara Buka Blokir Akun Sendiri',
                'answer' => 'Sayangnya, Anda tidak bisa membuka blokir akun sendiri jika terkunci secara permanen. Anda perlu menghubungi Customer Service kami melalui email atau telepon.',
                'order' => 1,
                'is_active' => true,
            ]);
        }
    }
}
