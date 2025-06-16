<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Models\Product; // Pastikan ini di-import untuk Route Model Binding
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController; // Jika Anda memang menggunakan ini

// Rute untuk homepage Miratara
Route::get('/', function () {
    return view('home.index');
})->name('homepage');

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