<?php

use Illuminate\Support\Facades\Route;

// PASTIKAN BARIS INI TIDAK ABU-ABU/BERWARNA HIJAU KOMENTAR
// Hapus tanda komentar (//) jika ada, atau pastikan tidak ada kesalahan sintaks di dekatnya
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;

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

// Rute untuk admin panel
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard'); // Ubah dari admin.dashboard menjadi dashboard karena prefix nama sudah ada

    // Manajemen User
    Route::get('/users', [UserController::class, 'index'])->name('users.index_page'); // Gunakan Controller
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create_page'); // Gunakan Controller

    // ---- Tambahkan Route API untuk data user di sini ----
    Route::get('/users-data', [UserController::class, 'getUsersJson'])->name('users.api_data');

    // --- Manajemen Produk (RESOURCE ROUTE) ---
    // Ganti semua Route::get produk individual dengan ini
    Route::resource('products', ProductController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']) // Method yang akan digunakan
        ->names([
            'index' => 'products.index_page',
            'create' => 'products.create_page',
            'store' => 'products.store',
            'edit' => 'products.edit_page',
            'update' => 'products.update',
            'destroy' => 'products.destroy',
        ]);

    // --- Manajemen Kategori (RESOURCE ROUTE) ---
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
});

// --- PENTING: IMPOR RUTE AUTENTIKASI BAWAAN LARAVEL BREEZE ---
require __DIR__.'/auth.php';