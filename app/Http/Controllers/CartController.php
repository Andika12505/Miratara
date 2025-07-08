<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    // Menampilkan halaman cart
    public function index()
    {
        $cartItems = Cart::content();
        return view('cart.index', compact('cartItems'));
    }
    // NEW: API endpoint untuk offcanvas - returns JSON data
    public function getCartData()
    {
        $cartItems = Cart::content();
        $cartCount = Cart::count();
        $cartTotal = Cart::total();
        $cartSubtotal = Cart::subtotal();

        return response()->json([
            'success' => true,
            'cartItems' => $cartItems,
            'cartCount' => $cartCount,
            'cartTotal' => $cartTotal,
            'cartSubtotal' => $cartSubtotal,
            'isEmpty' => $cartItems->isEmpty()
        ]);
    }

    // NEW: API endpoint to get cart offcanvas HTML
    // Replace your getCartOffcanvasContent() method in CartController.php with this:

    // Replace your getCartOffcanvasContent() method with this working version:

    public function getCartOffcanvasContent()
    {
        try {
            $cartItems = Cart::content();
            $cartCount = Cart::count();
            $cartTotal = Cart::total();
            
            // Since we know the data exists, let's render the view properly
            return view('components.cart-offcanvas-content', compact('cartItems', 'cartCount', 'cartTotal'))->render();
            
        } catch (\Exception $e) {
            \Log::error('Cart offcanvas view error: ' . $e->getMessage());
            
            return '<div class="text-center py-5 text-danger">' .
                '<h6>View Error:</h6>' .
                '<p>' . htmlspecialchars($e->getMessage()) . '</p>' .
                '<p><small>File: ' . $e->getFile() . ':' . $e->getLine() . '</small></p>' .
                '</div>';
        }
    }

    // UPDATED: Menambah item ke cart - now supports JSON response
    public function add(Request $request)
    {
        try {
            $product = Product::findOrFail($request->id);
            
            Cart::add(
                $product->id,
                $product->name,
                $request->quantity ?? 1,
                $product->price,
                ['image' => $product->image] // Opsi tambahan seperti gambar
            );

            // If request expects JSON (for AJAX), return JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil ditambahkan ke keranjang!',
                    'cartCount' => Cart::count(),
                    'cartTotal' => Cart::total()
                ]);
            }

            // Otherwise, return redirect (for regular form submission)
            return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
            
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan produk ke keranjang'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Gagal menambahkan produk ke keranjang');
        }
    }

    // Update kuantitas item
    public function update(Request $request, $rowId)
    {
        try {
            $quantity = (int) $request->quantity;
            
            // If quantity is 0 or less, remove the item instead of updating
            if ($quantity <= 0) {
                Cart::remove($rowId);
                $message = 'Produk berhasil dihapus dari keranjang!';
            } else {
                Cart::update($rowId, $quantity);
                $message = 'Keranjang berhasil diupdate!';
            }
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'cartCount' => Cart::count(),
                    'cartTotal' => Cart::total(),
                    'removed' => $quantity <= 0
                ]);
            }
            
            return redirect()->route('cart.index')->with('success', $message);
            
        } catch (\Exception $e) {
            \Log::error('Cart update error: ' . $e->getMessage());
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Gagal mengupdate keranjang'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Gagal mengupdate keranjang');
        }
    }
    // Menghapus item dari cart
    public function remove(Request $request, $rowId)
    {
        try {
            Cart::remove($rowId);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil dihapus dari keranjang!',
                    'cartCount' => Cart::count(),
                    'cartTotal' => Cart::total()
                ]);
            }
            
            return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang!');
            
        } catch (\Exception $e) {
            \Log::error('Cart remove error: ' . $e->getMessage());
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Gagal menghapus produk dari keranjang'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Gagal menghapus produk dari keranjang');
        }
    }

    // Mengosongkan cart
    public function clear(Request $request = null)
    {
        try {
            Cart::destroy();
            
            if ($request && ($request->expectsJson() || $request->ajax())) {
                return response()->json([
                    'success' => true,
                    'message' => 'Keranjang berhasil dikosongkan!',
                    'cartCount' => 0,
                    'cartTotal' => 0
                ]);
            }
            
            return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan!');
        } catch (\Exception $e) {
            if ($request && ($request->expectsJson() || $request->ajax())) {
                return response()->json(['success' => false, 'message' => 'Gagal mengosongkan keranjang'], 500);
            }
            return redirect()->back()->with('error', 'Gagal mengosongkan keranjang');
        }
    }
}