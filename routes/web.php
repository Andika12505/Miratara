<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Models\Product; // Pastikan ini di-import untuk Route Model Binding
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController; // Jika Anda memang menggunakan ini
use App\Http\Controllers\ProductController as PublicProductController; // Beri alias agar tidak konflik
use App\Http\Controllers\CustomerServiceController;

// Rute untuk homepage Miratara
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('homepage');

// Rute untuk halaman-halaman autentikasi KUSTOM Miratara Anda
Route::get('/masuk', function() {
    return view('auth.login');
})->name('login_page');

Route::get('/daftar', function() {
    return view('auth.register');
})->name('register_page');

Route::get('/checkout', function() {
    return "Ini halaman Checkout (belum diimplementasi)";
})->name('checkout_page');

// Route debug - bisa dihapus setelah selesai debugging
Route::get('/debug-user', function() {
    if (auth()->check()) {
        $user = auth()->user();
        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'is_admin' => $user->is_admin,
            'is_admin_type' => gettype($user->is_admin)
        ]);
    }
    return 'Not logged in';
})->middleware('auth');

// --- Rute Publik untuk Produk ---
// Pastikan memanggil PublicProductController yang baru Anda buat (tanpa Admin namespace)
Route::get('/products', [PublicProductController::class, 'index'])->name('products.index');

// Rute untuk admin panel
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    
    // Dashboard admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // === MANAJEMEN USER ===
    Route::resource('users', UserController::class)->except(['show'])
        ->names([
            'index' => 'users.index_page',
            'create' => 'users.create_page',
            'store' => 'users.store',
            'edit' => 'users.edit_page',
            'update' => 'users.update',
            'destroy' => 'users.destroy',
        ]);

    // Route alias untuk user (kompatibilitas dengan nama standar)
    Route::get('users-alias', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create-alias', [UserController::class, 'create'])->name('users.create');
    Route::get('users/{user}/edit-alias', [UserController::class, 'edit'])->name('users.edit');

    // Route khusus untuk API data tabel user (getUsersJson)
    Route::get('/users-data', [UserController::class, 'getUsersJson'])->name('users.data');

    // Route khusus untuk cek ketersediaan username/email/phone (checkAvailability)
    Route::post('/check-availability', [UserController::class, 'checkAvailability'])->name('check-availability');

    // === MANAJEMEN PRODUK ===
    Route::resource('products', ProductController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
        ->names([
            'index' => 'products.index_page',
            'create' => 'products.create_page',
            'store' => 'products.store',
            'edit' => 'products.edit_page',
            'update' => 'products.update',
            'destroy' => 'products.destroy',
        ]);
    
    // Route alias untuk product (kompatibilitas dengan nama standar)
    Route::get('products-alias', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create-alias', [ProductController::class, 'create'])->name('products.create');
    Route::get('products/{product}/edit-alias', [ProductController::class, 'edit'])->name('products.edit');

    // === MANAJEMEN KATEGORI ===
    Route::resource('categories', CategoryController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
        ->names([
            'index' => 'categories.index_page',
            'create' => 'categories.create_page',
            'store' => 'categories.store',
            'edit' => 'categories.edit_page',
            'update' => 'categories.update',
            'destroy' => 'categories.destroy',
        ]);
    
    // Route alias untuk categories (kompatibilitas dengan nama standar)
    Route::get('categories-alias', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create-alias', [CategoryController::class, 'create'])->name('categories.create');
    Route::get('categories/{category}/edit-alias', [CategoryController::class, 'edit'])->name('categories.edit');
});

// Rute autentikasi bawaan Laravel Breeze/Jetstream (biasanya ada di auth.php)
require __DIR__.'/auth.php';

// Jika Anda juga mengelola profil di admin, dan menggunakan ProfileController dari Breeze
/*
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
*/

// Route debug sederhana - tambahkan di routes/web.php
Route::get('/test-products', function() {
    try {
        $products = \App\Models\Product::all();
        
        $html = '<h1>🔍 Product Debug</h1>';
        $html .= '<p><strong>Total Products:</strong> ' . $products->count() . '</p><hr>';
        
        if ($products->isEmpty()) {
            $html .= '<p style="color: red;">❌ Tidak ada produk!</p>';
            return $html;
        }
        
        foreach ($products as $product) {
            $imagePath = $product->image ? public_path('images/' . $product->image) : null;
            $imageExists = $imagePath ? file_exists($imagePath) : false;
            
            $html .= '<div style="border: 1px solid #ddd; padding: 10px; margin: 10px 0;">';
            $html .= '<h3>' . $product->name . '</h3>';
            $html .= '<p><strong>ID:</strong> ' . $product->id . '</p>';
            $html .= '<p><strong>Image:</strong> ' . ($product->image ?: 'No image') . '</p>';
            $html .= '<p><strong>File Exists:</strong> ' . ($imageExists ? '✅ Yes' : '❌ No') . '</p>';
            $html .= '<p><strong>Full Path:</strong> ' . ($imagePath ?: 'N/A') . '</p>';
            $html .= '<p><strong>Stock:</strong> ' . $product->stock . '</p>';
            $html .= '<p><strong>Active:</strong> ' . ($product->is_active ? '✅ Yes' : '❌ No') . '</p>';
            
            if ($product->image && $imageExists) {
                $html .= '<p><strong>Preview:</strong><br>';
                $html .= '<img src="' . asset('images/' . $product->image) . '" style="max-width: 200px; height: auto;" /></p>';
            }
            
            $html .= '</div>';
        }
        
        return $html;
        
    } catch (\Exception $e) {
        return '<h1>❌ Error</h1><p>' . $e->getMessage() . '</p>';
    }
});

Route::prefix('cs')->name('cs.')->group(function () {
    Route::get('/', [CustomerServiceController::class, 'index'])->name('index'); // Halaman utama chatbot
    Route::post('/get-articles', [CustomerServiceController::class, 'getArticles'])->name('get_articles'); // Mengambil artikel/opsi via AJAX
    Route::post('/start-live-chat', [CustomerServiceController::class, 'startLiveChat'])->name('start_live_chat')->middleware('auth'); // Memulai sesi live chat
    Route::post('/submit-ticket', [CustomerServiceController::class, 'submitTicket'])->name('submit_ticket')->middleware('auth'); // Mengajukan tiket
});

// Newsletter
Route::post('/newsletter/subscribe', [App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Footer pages - Simple placeholders (create proper controllers later)
Route::get('/contact', function() {
    return view('pages.contact');
})->name('contact');

Route::get('/coming-soon', function() {
    return view('pages.coming-soon');
})->name('coming.soon');

Route::get('/order-status', function() {
    return view('pages.order-status');
})->name('order.status');

Route::get('/returns', function() {
    return view('pages.returns');
})->name('returns');

Route::get('/faqs', function() {
    return view('pages.faqs');
})->name('faqs');

Route::get('/services', function() {
    return view('pages.services');
})->name('services');

Route::get('/account', function() {
    return view('pages.account');
})->name('account');

Route::get('/stores', function() {
    return view('pages.stores');
})->name('stores');

Route::get('/product-care', function() {
    return view('pages.product-care');
})->name('product.care');

Route::get('/gift-cards', function() {
    return view('pages.gift-cards');
})->name('gift.cards');

Route::get('/about', function() {
    return view('pages.about');
})->name('about');

Route::get('/press', function() {
    return view('pages.press');
})->name('press');

Route::get('/careers', function() {
    return view('pages.careers');
})->name('careers');

Route::get('/sustainability', function() {
    return view('pages.sustainability');
})->name('sustainability');

Route::get('/legal', function() {
    return view('pages.legal');
})->name('legal');

Route::get('/cookies', function() {
    return view('pages.cookies');
})->name('cookies');