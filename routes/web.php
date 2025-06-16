<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product; // Pastikan ini di-import untuk Route Model Binding

// Rute untuk homepage Miratara
Route::get('/', function () {
    return view('home.index');
})->name('homepage');

// Rute untuk halaman-halaman autentikasi KUSTOM Miratara Anda
// URL di sini diubah untuk menghindari konflik dengan rute internal Breeze
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
// HANYA DILINDUNGI OLEH MIDDLEWARE 'auth' (user harus login)
Route::prefix('admin')->middleware(['auth'])->group(function () { // <-- HAPUS ', 'admin' DI SINI
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Manajemen User
    Route::get('/users', function() {
        return view('admin.users.index');
    })->name('admin.users.index_page');

    Route::get('/users/create', function() {
        return view('admin.users.create');
    })->name('admin.users.create_page');

    // --- Manajemen Produk ---
    Route::get('/products', function() {
        return view('admin.products.index');
    })->name('admin.products.index_page');

    Route::get('/products/create', function() {
        return view('admin.products.create');
    })->name('admin.products.create_page');

    // Untuk edit produk, menggunakan Route Model Binding
    Route::get('/products/{product}/edit', function(Product $product) {
        return view('admin.products.edit', compact('product'));
    })->name('admin.products.edit_page');
});

// --- PENTING: IMPOR RUTE AUTENTIKASI BAWAAN LARAVEL BREEZE ---
require __DIR__.'/auth.php';