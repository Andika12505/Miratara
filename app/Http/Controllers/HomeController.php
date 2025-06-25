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
    public function index(): View
    {
        // Ambil 6 produk terbaru yang aktif untuk ditampilkan di homepage
        $featuredProducts = Product::where('is_active', true)
            ->where('stock', '>', 0) // Hanya produk yang masih ada stoknya
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Jika produk kurang dari 6, ambil produk lainnya untuk memenuhi slot
        if ($featuredProducts->count() < 6) {
            $additionalProducts = Product::where('is_active', true)
                ->whereNotIn('id', $featuredProducts->pluck('id'))
                ->orderBy('created_at', 'desc')
                ->limit(6 - $featuredProducts->count())
                ->get();
            
            $featuredProducts = $featuredProducts->concat($additionalProducts);
        }

        return view('home.index', [
            'featuredProducts' => $featuredProducts
        ]);
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