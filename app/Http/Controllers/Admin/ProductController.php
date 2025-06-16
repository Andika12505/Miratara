<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; // Pastikan Model Product di-import
use Illuminate\Support\Facades\Storage; // Untuk mengelola penyimpanan file
use Illuminate\Support\Facades\Validator; // Untuk validasi input
use Illuminate\Validation\Rule; // Untuk aturan validasi unik dengan pengecualian
use Illuminate\Support\Str; // Untuk helper string seperti slug

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk dengan paginasi dan pencarian.
     * Digunakan oleh public/js/admin/admin_products.js
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);

        $productsQuery = Product::query(); // Mulai query untuk semua produk

        if ($search) {
            // Tambahkan kondisi pencarian jika ada parameter 'search'
            $productsQuery->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('category', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Ambil produk dengan paginasi, diurutkan berdasarkan tanggal dibuat terbaru
        $products = $productsQuery->orderBy('created_at', 'desc')->paginate($limit, ['*'], 'page', $page);

        // Format data produk untuk respons JSON ke frontend JavaScript
        $formattedProducts = $products->map(function($product) {
            return [
                'id' => $product->id,
                'name' => htmlspecialchars($product->name),
                'slug' => htmlspecialchars($product->slug),
                'description' => htmlspecialchars($product->description),
                'image_url_1' => $product->image_url_1, // Nama file gambar utama
                'image_url_2' => $product->image_url_2, // Nama file gambar sekunder
                'price' => (float)$product->price, // Pastikan tipe data float
                'discount_price' => $product->discount_price ? (float)$product->discount_price : null, // Null jika tidak ada diskon
                'category' => htmlspecialchars($product->category),
                'stock' => $product->stock,
                'is_active' => (bool)$product->is_active, // Pastikan tipe data boolean
                'created_at' => $product->created_at->format('d/m/Y H:i') // Format tanggal menggunakan Carbon
            ];
        });

        // Kembalikan respons dalam format JSON
        return response()->json([
            'success' => true,
            'data' => $formattedProducts,
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ],
            'search' => $search
        ]);
    }

    /**
     * Menyimpan produk baru ke database.
     * Digunakan oleh public/js/admin/admin_product_form.js (untuk create)
     */
    public function store(Request $request)
    {
        // Aturan validasi untuk setiap field produk
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug', // Slug harus unik
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price', // Harga diskon harus < harga normal
            'stock' => 'required|integer|min:0',
            'image1' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Gambar utama wajib, maks 2MB
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Gambar sekunder opsional
            'is_active' => 'boolean', // Harus boolean (true/false)
        ]);

        // Jika validasi gagal, kembalikan respons error JSON
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . $validator->errors()->first()
            ], 422); // HTTP 422 Unprocessable Entity
        }

        try {
            // Proses upload gambar utama
            $image1Path = $request->file('image1')->store('public/products'); // Simpan di storage/app/public/products
            $image1Name = basename($image1Path); // Ambil hanya nama file

            $image2Name = null;
            if ($request->hasFile('image2')) {
                // Proses upload gambar sekunder jika ada
                $image2Path = $request->file('image2')->store('public/products');
                $image2Name = basename($image2Path);
            }
            
            // Buat record produk baru di tabel 'products'
            $product = Product::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'image_url_1' => $image1Name,
                'image_url_2' => $image2Name,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'category' => $request->category,
                'stock' => $request->stock,
                'is_active' => $request->is_active,
            ]);

            // Kembalikan respons sukses JSON
            return response()->json([
                'success' => true,
                'message' => "Produk '{$product->name}' berhasil ditambahkan.",
                'product_data' => $product // Kirim data produk yang baru dibuat
            ]);

        } catch (\Exception $e) {
            // Log error untuk debugging di sisi server
            \Log::error("Error storing product: " . $e->getMessage(), ['exception' => $e]);
            // Kembalikan respons error generic ke frontend
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan produk. Silakan coba lagi.'
            ], 500); // HTTP 500 Internal Server Error
        }
    }

    /**
     * Menampilkan detail produk spesifik.
     * Digunakan oleh halaman edit produk (Route Model Binding)
     */
    public function show(Product $product)
    {
        // Route Model Binding secara otomatis mencari produk berdasarkan ID di URL
        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Memperbarui produk yang sudah ada di database.
     * Digunakan oleh public/js/admin/admin_product_form.js (untuk edit)
     */
    public function update(Request $request, Product $product)
    {
        // Aturan validasi untuk pembaruan produk
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)], // Slug unik, kecuali untuk produk ini sendiri
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Gambar opsional saat edit
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . $validator->errors()->first()
            ], 422);
        }

        try {
            $image1Name = $product->image_url_1; // Pertahankan nama gambar lama
            if ($request->hasFile('image1')) {
                // Hapus gambar lama jika ada
                if ($product->image_url_1 && Storage::disk('public')->exists('products/' . $product->image_url_1)) {
                    Storage::disk('public')->delete('products/' . $product->image_url_1);
                }
                $image1Path = $request->file('image1')->store('public/products');
                $image1Name = basename($image1Path);
            }

            $image2Name = $product->image_url_2;
            if ($request->hasFile('image2')) {
                if ($product->image_url_2 && Storage::disk('public')->exists('products/' . $product->image_url_2)) {
                    Storage::disk('public')->delete('products/' . $product->image_url_2);
                }
                $image2Path = $request->file('image2')->store('public/products');
                $image2Name = basename($image2Path);
            }
            
            // Perbarui record produk di database
            $product->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'image_url_1' => $image1Name,
                'image_url_2' => $image2Name,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'category' => $request->category,
                'stock' => $request->stock,
                'is_active' => $request->is_active,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Produk '{$product->name}' berhasil diperbarui.",
                'product_data' => $product
            ]);

        } catch (\Exception $e) {
            \Log::error("Error updating product: " . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui produk. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Menghapus produk dari database.
     * Digunakan oleh public/js/admin/admin_products.js
     */
    public function destroy(Product $product)
    {
        try {
            // Hapus gambar terkait dari storage jika ada
            if ($product->image_url_1 && Storage::disk('public')->exists('products/' . $product->image_url_1)) {
                Storage::disk('public')->delete('products/' . $product->image_url_1);
            }
            if ($product->image_url_2 && Storage::disk('public')->exists('products/' . $product->image_url_2)) {
                Storage::disk('public')->delete('products/' . $product->image_url_2);
            }

            // Hapus produk dari database
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => "Produk '{$product->name}' berhasil dihapus."
            ]);

        } catch (\Exception $e) {
            \Log::error("Error deleting product: " . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus produk. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Memeriksa ketersediaan slug untuk validasi unik.
     * Digunakan oleh public/js/admin/admin_product_form.js
     */
    public function checkSlugAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'required|string|max:255',
            'id' => 'nullable|integer', // ID produk jika mode edit
        ]);

        if ($validator->fails()) {
            return response()->json(['available' => false, 'message' => 'Invalid request.'], 422);
        }

        $query = Product::where('slug', $request->slug);

        if ($request->id) {
            $query->where('id', '!=', $request->id); // Abaikan produk ini sendiri saat edit
        }

        $available = !$query->exists();

        return response()->json(['available' => $available]);
    }
}