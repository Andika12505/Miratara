<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart; // <-- GANTI DENGAN INI

class CartController extends Controller
{
    // Menampilkan halaman cart
    public function index()
    {
        // Package ini menyebut item sebagai 'content'
        $cartItems = Cart::content();
        return view('cart.index', compact('cartItems'));
    }

    // Menambah item ke cart
    public function add(Request $request)
    {
        $product = Product::findOrFail($request->id);

        Cart::add(
            $product->id, 
            $product->name, 
            $request->quantity, 
            $product->price, 
            ['image' => $product->image] // Opsi tambahan seperti gambar
        );

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Update kuantitas item
    public function update(Request $request, $rowId) // Menggunakan $rowId, bukan $id
    {
        Cart::update($rowId, $request->quantity);
        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diupdate!');
    }

    // Menghapus item dari cart
    public function remove($rowId) // Menggunakan $rowId, bukan $id
    {
        Cart::remove($rowId);
        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    // Mengosongkan cart
    public function clear()
    {
        // Package ini menggunakan destroy() untuk menghapus semua
        Cart::destroy();
        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan!');
    }
}