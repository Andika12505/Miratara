<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman homepage dengan featured products.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Ambil data dari database
        $products = Product::latest()->get();

        // 2. Kirim variabel 'products' ke view
        return view('home.index', ['products' => $products]);
    }

    /**
     * Mendapatkan produk dengan diskon untuk carousel atau section khusus.
     *
     * @return \Illuminate\View\View
     */
    //public function getDiscountedProducts()
    /*{
        $discountedProducts = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->whereNotNull('metadata->is_discounted')
            ->whereJsonContains('metadata->is_discounted', true)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return $discountedProducts;
    }*/
}