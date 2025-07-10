<?php
// app/Http/Controllers/Admin/ProductController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $productsQuery = Product::with('category');

        if ($search) {
            $productsQuery->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
            })->orWhereHas('category', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            });
        }

        $products = $productsQuery->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.products.index', compact('products', 'search'));
    }

    /**
     * Menampilkan form untuk membuat produk baru.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Menyimpan produk baru ke database.
     */
    public function store(ProductRequest $request)
    {
        try {
            $validated = $request->validated();

            // Handle image upload - save only filename
            $imageFilename = null;
            if ($request->hasFile('image')) {
                $imageFilename = $this->handleImageUpload($request->file('image'), $validated['name']);
            }

            // Build metadata JSON from form fields
            $metadata = $this->buildMetadataFromRequest($request);

            $product = Product::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => Str::slug($validated['slug']),
                'description' => $validated['description'],
                'image' => $imageFilename, // Only filename, no path
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'metadata' => $metadata,
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Produk berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail produk.
     */
    public function show(Product $product)
    {
        $product->load('category');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Menampilkan form untuk mengedit produk.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Memperbarui produk di database.
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            $validated = $request->validated();

            // Handle image upload
            $imageFilename = $product->image; // Keep existing image
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image && Storage::disk('public')->exists('products/' . $product->image)) {
                    Storage::disk('public')->delete('products/' . $product->image);
                }
                $imageFilename = $this->handleImageUpload($request->file('image'), $validated['name']);
            } elseif ($request->input('clear_image')) {
                // Clear image option
                if ($product->image && Storage::disk('public')->exists('products/' . $product->image)) {
                    Storage::disk('public')->delete('products/' . $product->image);
                }
                $imageFilename = null;
            }

            // Build metadata JSON from form fields
            $metadata = $this->buildMetadataFromRequest($request);

            $product->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => Str::slug($validated['slug']),
                'description' => $validated['description'],
                'image' => $imageFilename,
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'metadata' => $metadata,
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Produk berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus produk dari database.
     */
    public function destroy(Product $product)
    {
        try {
            // Delete associated image
            if ($product->image && Storage::disk('public')->exists('products/' . $product->image)) {
                Storage::disk('public')->delete('products/' . $product->image);
            }

            $product->delete();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Produk berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Handle image upload and return only filename
     */
    private function handleImageUpload($file, $productName): string
    {
        // Generate unique filename based on product name and timestamp
        $filename = Str::slug($productName) . '_' . time() . '.png';
        
        // Store in public/products directory
        $file->storeAs('public/products', $filename);
        
        return $filename; // Return only filename, no path
    }

    /**
     * Build metadata JSON structure from form request
     */
    private function buildMetadataFromRequest(Request $request): array
    {
        $metadata = [
            'vibe_attributes' => [],
            'general_tags' => $request->input('general_tags', []),
            'origin' => $request->input('origin')
        ];

        // Build vibe_attributes
        $vibeFields = ['occasion', 'style', 'material', 'color_tone', 'fit', 'pattern', 'neckline', 'sleeve_length'];
        
        foreach ($vibeFields as $field) {
            $value = $request->input("vibe_{$field}");
            if (!empty($value)) {
                $metadata['vibe_attributes'][$field] = $value;
            }
        }

        // Remove empty arrays
        $metadata['vibe_attributes'] = array_filter($metadata['vibe_attributes']);
        $metadata['general_tags'] = array_filter($metadata['general_tags']);

        return $metadata;
    }

    /**
     * Check slug availability (AJAX endpoint)
     */
    public function checkSlugAvailability(Request $request): JsonResponse
    {
        $slug = $request->input('slug');
        $productId = $request->input('product_id'); // For edit mode

        $query = Product::where('slug', $slug);
        
        if ($productId) {
            $query->where('id', '!=', $productId);
        }

        $exists = $query->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Slug sudah digunakan' : 'Slug tersedia'
        ]);
    }

    /**
     * Get metadata options for form building
     */
    public function getMetadataOptions(): JsonResponse
    {
        return response()->json([
            'vibe_attributes' => [
                'occasion' => ['casual', 'formal', 'party', 'work', 'sport', 'vacation', 'daily'],
                'style' => ['vintage', 'modern', 'classic', 'trendy', 'minimalist', 'bohemian', 'streetwear'],
                'material' => ['cotton', 'polyester', 'wool', 'silk', 'linen', 'denim', 'leather', 'viscose'],
                'color_tone' => ['bright', 'pastel', 'dark', 'neutral', 'earth', 'neon', 'metallic'],
                'fit' => ['slim', 'regular', 'loose', 'oversized', 'fitted', 'relaxed'],
                'pattern' => ['solid', 'striped', 'floral', 'geometric', 'abstract', 'polka_dots', 'checkered'],
                'neckline' => ['round', 'v_neck', 'crew', 'scoop', 'high_neck', 'off_shoulder', 'halter'],
                'sleeve_length' => ['sleeveless', 'short_sleeve', 'long_sleeve', '3_quarter', 'cap_sleeve']
            ],
            'general_tags' => ['comfortable', 'elegant', 'sporty', 'sexy', 'professional', 'casual_wear', 'evening_wear'],
            'origins' => ['Indonesia', 'China', 'Vietnam', 'India', 'USA', 'Turkey', 'Bangladesh']
        ]);
    }

    /**
     * Bulk operations for products
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'action' => 'required|in:activate,deactivate,delete',
        ]);

        $productIds = $request->input('product_ids');
        $action = $request->input('action');

        try {
            switch ($action) {
                case 'activate':
                    Product::whereIn('id', $productIds)->update(['is_active' => true]);
                    $message = count($productIds) . ' produk berhasil diaktifkan.';
                    break;
                    
                case 'deactivate':
                    Product::whereIn('id', $productIds)->update(['is_active' => false]);
                    $message = count($productIds) . ' produk berhasil dinonaktifkan.';
                    break;
                    
                case 'delete':
                    $products = Product::whereIn('id', $productIds)->get();
                    
                    // Delete images
                    foreach ($products as $product) {
                        if ($product->image && Storage::disk('public')->exists('products/' . $product->image)) {
                            Storage::disk('public')->delete('products/' . $product->image);
                        }
                    }
                    
                    Product::whereIn('id', $productIds)->delete();
                    $message = count($productIds) . ' produk berhasil dihapus.';
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export products data
     */
    public function export(Request $request)
    {
        // This can be implemented later for CSV/Excel export
        // For now, just return JSON data
        $products = Product::with('category')->get();
        
        return response()->json($products);
    }
}