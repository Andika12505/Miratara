<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product; // Import model Product
use Illuminate\Http\Request;
use Illuminate\View\View; // Untuk mengembalikan view

class ProductController extends Controller
{
    /**
     * Menampilkan daftar semua produk di halaman publik.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        // Ambil parameter sorting dari request, default 'newest'
        $sortBy = $request->query('sort_by', 'newest');
        $limit = $request->query('limit', 12); // Jumlah produk per halaman, default 12

        $productsQuery = Product::query();

        // Logika Sorting
        switch ($sortBy) {
            case 'price_asc':
                $productsQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $productsQuery->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $productsQuery->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $productsQuery->orderBy('name', 'desc');
                break;
            case 'newest':
            default:
                $productsQuery->orderBy('created_at', 'desc');
                break;
        }

        // Ambil produk dengan paginasi
        $products = $productsQuery->paginate($limit);

        // Kirim data produk dan parameter sorting ke view
        return view('products.index', [
            'products' => $products,
            'sortBy' => $sortBy,
            'limit' => $limit,
        ]);
    }
}