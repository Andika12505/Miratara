<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController; // Pastikan ini di-import

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// --- Rute API untuk Admin Management ---
// Middleware 'auth:sanctum' bisa ditambahkan jika Anda menggunakan autentikasi API khusus
Route::prefix('admin')->group(function () {
    // User Management API
    //Route::get('users', [UserController::class, 'index'])->name('api.admin.users.index');
    Route::post('users', [UserController::class, 'store'])->name('api.admin.users.store');
    Route::post('users/delete', [UserController::class, 'destroy'])->name('api.admin.users.destroy');
    Route::post('check-availability', [UserController::class, 'checkAvailability'])->name('api.admin.check_availability');

    // Product Management API (menggunakan Route::apiResource untuk CRUD RESTful)
    // Ini akan secara otomatis mendaftarkan rute GET, POST, PUT/PATCH, DELETE untuk products
    Route::apiResource('products', ProductController::class);
    // Tambahkan rute khusus jika diperlukan, misal untuk slug availability
    Route::post('check-slug-availability', [ProductController::class, 'checkSlugAvailability'])->name('api.admin.check_slug_availability');
});