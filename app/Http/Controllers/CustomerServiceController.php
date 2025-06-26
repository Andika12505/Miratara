<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CsArticle; // Import model CsArticle

class CustomerServiceController extends Controller
{
    /**
     * Menampilkan halaman utama chatbot CS.
     */
    public function index()
    {
        // Mengambil artikel level 1 (parent_id = NULL)
        $rootArticles = CsArticle::root()->orderBy('order')->get();
        return view('customer.cs.chatbot', compact('rootArticles'));
    }

    /**
     * Mengambil sub-artikel atau jawaban berdasarkan parent_id (AJAX).
     */
    public function getArticles(Request $request)
    {
        $parentId = $request->input('parent_id');

        if ($parentId === null) {
            // Jika parent_id null, ambil artikel akar (level 1)
            $articles = CsArticle::root()->orderBy('order')->get();
        } else {
            // Ambil artikel anak dari parent_id yang diberikan
            $articles = CsArticle::where('parent_id', $parentId)->where('is_active', true)->orderBy('order')->get();
        }

        // Format respons untuk frontend
        $formattedArticles = $articles->map(function ($article) {
            return [
                'id' => $article->id,
                'question' => $article->question,
                'answer' => $article->answer, // Jika ada jawaban langsung
                'has_children' => $article->children()->exists(), // Cek apakah ada sub-pilihan
                'is_chat_option' => ($article->question === 'Butuh bantuan lebih lanjut? Chat dengan Admin.'), // Tanda untuk UI live chat
            ];
        });

        return response()->json([
            'success' => true,
            'articles' => $formattedArticles,
        ]);
    }

    // --- Placeholder untuk Live Chat atau Ticket (akan diimplementasikan nanti) ---
    public function startLiveChat(Request $request)
    {
        // Logika untuk memulai sesi live chat
        // (Misalnya, membuat LiveChatSession baru, menandai user_id, dll.)
        // Anda juga bisa memeriksa ketersediaan agen di sini
        return response()->json(['success' => true, 'message' => 'Sesi chat dimulai.', 'session_id' => 123]);
    }

    public function submitTicket(Request $request)
    {
        // Logika untuk mengajukan tiket formal
        return response()->json(['success' => true, 'message' => 'Tiket Anda telah diajukan.']);
    }
}
