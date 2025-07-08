<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; // Import model Product
use App\Models\Category; // Import model Category untuk dropdown
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk upload file
use Illuminate\Validation\Rule; // Untuk validasi unik

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $productsQuery = Product::with('category'); // Eager load relasi kategori

        if ($search) {
            $productsQuery->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
            })->orWhereHas('category', function ($query) use ($search) { // Cari juga di nama kategori
                $query->where('name', 'LIKE', "%{$search}%");
            });
        }

        $products = $productsQuery->orderBy('created_at', 'desc')->paginate(10); // Paginate 10 produk per halaman

        return view('admin.products.index', compact('products', 'search'));
    }

    /**
     * Menampilkan form untuk membuat produk baru.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get(); // Ambil semua kategori untuk dropdown
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Menyimpan produk baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'metadata' => 'nullable|json', // Validasi input harus JSON string
            'is_active' => 'boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/products'); // Simpan gambar di storage/app/public/products
        }

        Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => \Illuminate\Support\Str::slug($validated['slug']),
            'description' => $validated['description'],
            'image' => $imagePath ? Storage::url($imagePath) : null, // Simpan URL publik
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'metadata' => json_decode($validated['metadata'], true) ?? null, // Decode JSON string
            'is_active' => $request->has('is_active'), // Checkbox akan mengirim nilai jika dicentang
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
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
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->ignore($product->id), // Abaikan produk saat ini
            ],
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'metadata' => 'nullable|json',
            'is_active' => 'boolean',
        ]);

        $imagePath = $product->image; // Pertahankan gambar lama
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada dan bukan gambar default
            if ($product->image && Storage::exists(str_replace('/storage', 'public', $product->image))) {
                 Storage::delete(str_replace('/storage', 'public', $product->image));
            }
            $imagePath = $request->file('image')->store('public/products');
        } elseif ($request->input('clear_image')) { // Opsi untuk menghapus gambar
            if ($product->image && Storage::exists(str_replace('/storage', 'public', $product->image))) {
                 Storage::delete(str_replace('/storage', 'public', $product->image));
            }
            $imagePath = null;
        }

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => \Illuminate\Support\Str::slug($validated['slug']),
            'description' => $validated['description'],
            'image' => $imagePath ? Storage::url($imagePath) : null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'metadata' => json_decode($validated['metadata'], true) ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk dari database.
     */
    public function destroy(Product $product)
    {
        // Hapus gambar terkait saat produk dihapus
        if ($product->image && Storage::exists(str_replace('/storage', 'public', $product->image))) {
             Storage::delete(str_replace('/storage', 'public', $product->image));
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}