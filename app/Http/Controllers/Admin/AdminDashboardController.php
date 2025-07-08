<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard admin.
     */
    public function index(): View
    {
        // Untuk saat ini, kita hanya menampilkan view.
        // Nanti kita bisa tambahkan data seperti jumlah user, produk, dll.
        // $userCount = \App\Models\User::count();
        // return view('admin.dashboard', ['userCount' => $userCount]);

        return view('admin.dashboard');
    }
}