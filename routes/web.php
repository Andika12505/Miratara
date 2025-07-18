<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
// Import Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController as PublicProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\StockManagementController;
use App\Http\Controllers\CustomerAccountController;
use App\Http\Controllers\CustomerServiceController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\AdminDashboardController; 

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

// NEW: Product detail page route
Route::get('/products/{slug}', [PublicProductController::class, 'show'])->name('products.show');

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

    // Cart routes - MUST be in web middleware group for session access
    Route::middleware(['web'])->prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/update/{rowId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{rowId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('cart.clear');
    
    // API routes for offcanvas
    Route::get('/data', [CartController::class, 'getCartData'])->name('cart.data');
    Route::get('/offcanvas-content', [CartController::class, 'getCartOffcanvasContent'])->name('cart.offcanvas.content');
});

});

/*
|--------------------------------------------------------------------------
| Rute untuk Admin Panel
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen User, Produk, Kategori
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);


    // Stock Management Routes
    Route::prefix('stock')->name('stock.')->group(function () {
        // Main stock management pages
        Route::get('/', [App\Http\Controllers\Admin\StockManagementController::class, 'index'])->name('index');
        Route::get('/movements', [App\Http\Controllers\Admin\StockManagementController::class, 'movements'])->name('movements');
        Route::get('/alerts', [App\Http\Controllers\Admin\StockManagementController::class, 'alerts'])->name('alerts');
        Route::get('/reports', [App\Http\Controllers\Admin\StockManagementController::class, 'reports'])->name('reports');
        
        // Product stock management
        Route::get('/product/{product}', [App\Http\Controllers\Admin\StockManagementController::class, 'show'])->name('show');
        
        // Stock operations
        Route::post('/product/{product}/add', [App\Http\Controllers\Admin\StockManagementController::class, 'addStock'])->name('add');
        Route::post('/product/{product}/remove', [App\Http\Controllers\Admin\StockManagementController::class, 'removeStock'])->name('remove');
        Route::post('/product/{product}/adjust', [App\Http\Controllers\Admin\StockManagementController::class, 'adjustStock'])->name('adjust');
        Route::post('/product/{product}/settings', [App\Http\Controllers\Admin\StockManagementController::class, 'updateSettings'])->name('update_settings');
        
        // Bulk operations
        Route::post('/bulk-update', [App\Http\Controllers\Admin\StockManagementController::class, 'bulkUpdate'])->name('bulk_update');
        
        // Alert management
        Route::post('/alerts/{alert}/acknowledge', [App\Http\Controllers\Admin\StockManagementController::class, 'acknowledgeAlert'])->name('acknowledge_alert');
        Route::post('/alerts/{alert}/resolve', [App\Http\Controllers\Admin\StockManagementController::class, 'resolveAlert'])->name('resolve_alert');
        
        // Export functions
        Route::get('/export', [App\Http\Controllers\Admin\StockManagementController::class, 'export'])->name('export');
    });

    // Dashboard AJAX routes
    Route::get('/dashboard/stock-data', [AdminDashboardController::class, 'getStockData'])->name('dashboard.stock_data');
    Route::post('/dashboard/quick-action', [AdminDashboardController::class, 'quickStockAction'])->name('dashboard.quick_action');
    Route::get('/dashboard/analytics', [AdminDashboardController::class, 'stockAnalytics'])->name('dashboard.analytics');
    
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
| Cart Routes (Public - but needs session)
|--------------------------------------------------------------------------
*/
Route::middleware(['web'])->prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    
    // Update quantity - should accept both POST and PATCH
    Route::match(['POST', 'PATCH'], '/update/{rowId}', [CartController::class, 'update'])->name('cart.update');
    
    // Remove item - should accept both DELETE and POST  
    Route::match(['DELETE', 'POST'], '/remove/{rowId}', [CartController::class, 'remove'])->name('cart.remove');
    
    // Clear cart - should accept both DELETE and POST
    Route::match(['DELETE', 'POST'], '/clear', [CartController::class, 'clear'])->name('cart.clear');
    
    // API routes for offcanvas
    Route::get('/data', [CartController::class, 'getCartData'])->name('cart.data');
    Route::get('/offcanvas-content', [CartController::class, 'getCartOffcanvasContent'])->name('cart.offcanvas.content');
});

/*
|--------------------------------------------------------------------------
| Rute Autentikasi & Debug
|--------------------------------------------------------------------------
*/

// Rute autentikasi bawaan Laravel (biasanya dari auth.php)
require __DIR__.'/auth.php';
