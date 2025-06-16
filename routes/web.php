<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController;

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
// Tambahkan 'is_admin' middleware di sini
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'is_admin'])->group(function () {
    // Middleware 'verified' umumnya ditambahkan jika Anda punya fitur verifikasi email
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Manajemen User menggunakan Route::resource
    Route::resource('users', UserController::class)->except(['show']);

    // Route khusus untuk API data tabel user (getUsersJson)
    Route::get('/users-data', [UserController::class, 'getUsersJson'])->name('users.data');

    // Route khusus untuk cek ketersediaan username/email/phone (checkAvailability)
    Route::post('/check-availability', [UserController::class, 'checkAvailability'])->name('check-availability');

    // --- Manajemen Produk ---
    Route::resource('products', ProductController::class)->except(['show']);
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
