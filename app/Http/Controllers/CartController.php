<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        $cartItems = Cart::content();
        return view('cart.index', compact('cartItems'));
    }

    /**
     * Get cart data as JSON for API endpoints
     */
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

    /**
     * Get cart offcanvas HTML content
     */
    public function getCartOffcanvasContent()
    {
        try {
            $cartItems = Cart::content();
            $cartCount = Cart::count();
            $cartTotal = Cart::total();
            
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

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        try {
            $product = Product::findOrFail($request->id);
            
            // Validate and prepare cart data
            $validatedData = $this->validateCartAddition($request, $product);
            $cartData = $this->prepareCartData($product, $validatedData);
            
            // Add to cart
            Cart::add(
                $cartData['id'],
                $product->name,
                $cartData['quantity'],
                $product->price,
                $cartData['options']
            );

            $successMessage = $this->buildSuccessMessage($product, $cartData);

            return $this->handleResponse($request, [
                'success' => true,
                'message' => $successMessage,
                'cartCount' => Cart::count(),
                'cartTotal' => Cart::total(),
                'sizeInfo' => $cartData['sizeInfo'] ?? null
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->handleValidationError($request, $e);
        } catch (\Exception $e) {
            return $this->handleError($request, $e->getMessage());
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $rowId)
    {
        try {
            $quantity = (int) $request->quantity;
            
            if ($quantity <= 0) {
                Cart::remove($rowId);
                $message = 'Product removed from cart successfully!';
                $removed = true;
            } else {
                Cart::update($rowId, $quantity);
                $message = 'Cart updated successfully!';
                $removed = false;
            }
            
            return $this->handleResponse($request, [
                'success' => true,
                'message' => $message,
                'cartCount' => Cart::count(),
                'cartTotal' => Cart::total(),
                'removed' => $removed
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Cart update error: ' . $e->getMessage());
            return $this->handleError($request, 'Failed to update cart');
        }
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request, $rowId)
    {
        try {
            Cart::remove($rowId);
            
            return $this->handleResponse($request, [
                'success' => true,
                'message' => 'Product removed from cart successfully!',
                'cartCount' => Cart::count(),
                'cartTotal' => Cart::total()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Cart remove error: ' . $e->getMessage());
            return $this->handleError($request, 'Failed to remove product from cart');
        }
    }

    /**
     * Clear entire cart
     */
    public function clear(Request $request)
    {
        try {
            \Log::info('Clear cart method called');
            Cart::destroy();
            \Log::info('Cart cleared successfully. Count: ' . Cart::count());
            
            if ($request->isMethod('POST')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart cleared successfully!',
                    'cartCount' => Cart::count(),
                    'cartTotal' => Cart::total()
                ]);
            }
            
            return redirect()->route('cart.index')->with('success', 'Cart cleared successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Clear cart error: ' . $e->getMessage());
            
            if ($request->isMethod('POST')) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to clear cart');
        }
    }

    /**
     * Validate cart addition request
     */
    private function validateCartAddition(Request $request, Product $product)
    {
        $rules = [
            'id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1|max:99'
        ];
        
        $messages = [
            'id.required' => 'Product ID is required.',
            'id.exists' => 'Product not found.',
            'quantity.integer' => 'Quantity must be a valid number.',
            'quantity.min' => 'Quantity must be at least 1.',
            'quantity.max' => 'Maximum quantity per addition is 99.'
        ];
        
        // Add size validation if product has sizes
        if ($product->hasSizes()) {
            $rules['size_id'] = [
                'required',
                'exists:sizes,id',
                function ($attribute, $value, $fail) use ($product) {
                    $sizeExists = $product->sizes()
                        ->where('size_id', $value)
                        ->where('product_sizes.is_available', true)
                        ->where('product_sizes.stock', '>', 0)
                        ->exists();
                    
                    if (!$sizeExists) {
                        $fail('The selected size is not available for this product.');
                    }
                }
            ];
            
            $messages = array_merge($messages, [
                'size_id.required' => 'Please select a size for this product.',
                'size_id.exists' => 'Selected size is not valid.'
            ]);
        }
        
        return $request->validate($rules, $messages);
    }

    /**
     * Prepare cart data for addition
     */
    private function prepareCartData(Product $product, array $validatedData)
    {
        $quantity = $validatedData['quantity'] ?? 1;
        
        if ($product->hasSizes() && isset($validatedData['size_id'])) {
            $selectedSize = $product->sizes()->where('size_id', $validatedData['size_id'])->first();
            
            // Validate stock for selected size
            if (!$selectedSize || !$selectedSize->pivot->is_available || $selectedSize->pivot->stock <= 0) {
                throw new \Exception('Selected size is currently out of stock.');
            }
            
            if ($quantity > $selectedSize->pivot->stock) {
                throw new \Exception("Only {$selectedSize->pivot->stock} items available in size {$selectedSize->name}.");
            }
            
            return [
                'id' => $product->id . '_size_' . $selectedSize->id,
                'quantity' => $quantity,
                'options' => [
                    'image' => $product->image,
                    'size_id' => $selectedSize->id,
                    'size_name' => $selectedSize->name,
                    'size_display' => $selectedSize->display,
                    'has_size' => true
                ],
                'sizeInfo' => [
                    'size_id' => $selectedSize->id,
                    'size_name' => $selectedSize->name,
                    'size_display' => $selectedSize->display
                ]
            ];
        } else {
            // Product without sizes
            if ($quantity > $product->stock) {
                throw new \Exception("Only {$product->stock} items available.");
            }
            
            return [
                'id' => $product->id,
                'quantity' => $quantity,
                'options' => [
                    'image' => $product->image,
                    'has_size' => false
                ]
            ];
        }
    }

    /**
     * Build success message for cart addition
     */
    private function buildSuccessMessage(Product $product, array $cartData)
    {
        $sizeText = isset($cartData['sizeInfo']) ? " (Size: {$cartData['sizeInfo']['size_name']})" : "";
        return "Product{$sizeText} added to cart successfully!";
    }

    /**
     * Handle response based on request type (AJAX or regular)
     */
    private function handleResponse(Request $request, array $data)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($data);
        }
        
        $message = $data['message'];
        $status = $data['success'] ? 'success' : 'error';
        
        return redirect()->route('cart.index')->with($status, $message);
    }

    /**
     * Handle validation errors
     */
    private function handleValidationError(Request $request, \Illuminate\Validation\ValidationException $e)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        }
        
        return redirect()->back()->withErrors($e->validator)->withInput();
    }

    /**
     * Handle general errors
     */
    private function handleError(Request $request, string $message)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 500);
        }
        
        return redirect()->back()->with('error', $message);
    }
}