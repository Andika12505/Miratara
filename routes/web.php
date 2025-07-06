<?php

use Illuminate\Support\Facades\Route;
// Import Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController as PublicProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\CustomerAccountController;
use App\Http\Controllers\CustomerServiceController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Rute Publik & Halaman Utama
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('homepage');

// Rute untuk halaman-halaman autentikasi kustom
Route::get('/masuk', function() { return view('auth.login'); })->name('login_page');
Route::get('/daftar', function() { return view('auth.register'); })->name('register_page');

// Rute publik lainnya
Route::get('/checkout', function() { return "Ini halaman Checkout (belum diimplementasi)"; })->name('checkout_page');
Route::get('/products', [PublicProductController::class, 'index'])->name('products.index');

/*
|--------------------------------------------------------------------------
| Rute untuk Pengguna yang Sudah Login (Customer Area)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // --- GRUP UNTUK AKUN PELANGGAN ---
    // Semua URL di sini akan diawali dengan /account, misal: /account, /account/update-profile
    Route::prefix('account')->name('customer.account.')->group(function () {
        Route::get('/', [CustomerAccountController::class, 'viewAccount'])->name('view');
        Route::post('/update-profile', [CustomerAccountController::class, 'updateProfile'])->name('update_profile');
        Route::post('/update-password', [CustomerAccountController::class, 'updatePassword'])->name('update_password');
    });

    // --- GRUP UNTUK CUSTOMER SERVICE ---
    Route::prefix('cs')->name('cs.')->group(function () {
        Route::get('/', [CustomerServiceController::class, 'index'])->name('index');
        Route::post('/get-articles', [CustomerServiceController::class, 'getArticles'])->name('get_articles');
        Route::post('/start-live-chat', [CustomerServiceController::class, 'startLiveChat'])->name('start_live_chat');
        Route::post('/submit-ticket', [CustomerServiceController::class, 'submitTicket'])->name('submit_ticket');
    });

});

/*
|--------------------------------------------------------------------------
| Rute untuk Admin Panel
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'is_admin'])->group(function () {
    
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');

    // Manajemen User, Produk, Kategori
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    
    // Rute API/Data untuk Admin
    Route::get('/users-data', [UserController::class, 'getUsersJson'])->name('users.data');
    Route::post('/check-availability', [UserController::class, 'checkAvailability'])->name('check-availability');
});

/*
|--------------------------------------------------------------------------
| Rute Lain-lain & Halaman Statis
|--------------------------------------------------------------------------
*/

// Newsletter Subscription
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Halaman statis di footer
Route::get('/contact', function() { return view('pages.contact'); })->name('contact');
Route::get('/coming-soon', function() { return view('pages.coming-soon'); })->name('coming.soon');
Route::get('/order-status', function() { return view('pages.order-status'); })->name('order.status');
Route::get('/returns', function() { return view('pages.returns'); })->name('returns');
Route::get('/faqs', function() { return view('pages.faqs'); })->name('faqs');
Route::get('/services', function() { return view('pages.services'); })->name('services');
Route::get('/stores', function() { return view('pages.stores'); })->name('stores');
Route::get('/product-care', function() { return view('pages.product-care'); })->name('product.care');
Route::get('/gift-cards', function() { return view('pages.gift-cards'); })->name('gift.cards');


/*
|--------------------------------------------------------------------------
| Rute Autentikasi & Debug
|--------------------------------------------------------------------------
*/

// Rute autentikasi bawaan Laravel (biasanya dari auth.php)
require __DIR__.'/auth.php';

// Rute untuk Debug (bisa dihapus nanti)
Route::get('/debug-user', function() {
    return Auth::check() ? response()->json(Auth::user()) : 'Not logged in';
})->middleware('auth');

Route::get('/test-products', function() {
    // ... (kode debug produk Anda tetap di sini)
});