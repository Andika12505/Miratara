<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product; // Pastikan ini di-import untuk Route Model Binding

// PASTIKAN BARIS INI TIDAK ABU-ABU/BERWARNA HIJAU KOMENTAR
// Hapus tanda komentar (//) jika ada, atau pastikan tidak ada kesalahan sintaks di dekatnya
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

    // --- Manajemen Produk ---
    Route::get('/products', [ProductController::class, 'index'])->name('products.index_page'); // Gunakan Controller
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create_page'); // Gunakan Controller

    // Untuk edit produk, menggunakan Route Model Binding
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit_page'); // Gunakan Controller
});

// --- PENTING: IMPOR RUTE AUTENTIKASI BAWAAN LARAVEL BREEZE ---
require __DIR__.'/auth.php';