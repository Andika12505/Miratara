<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category; // Import model Category
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Untuk validasi unik

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori.
     */
    public function index()
    {
        $categories = Category::orderBy('name')->get(); // Ambil semua kategori, urutkan berdasarkan nama
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Menampilkan form untuk membuat kategori baru.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'required|string|max:255|unique:categories,slug',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->slug), // Pastikan slug di-format dengan baik
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail kategori (opsional, mungkin tidak perlu view terpisah untuk ini di admin).
     */
    public function show(Category $category)
    {
        // return view('admin.categories.show', compact('category'));
        // Untuk admin, biasanya detail ditampilkan di index atau edit page
        return redirect()->route('admin.categories.edit_page', $category);
    }

    /**
     * Menampilkan form untuk mengedit kategori.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Memperbarui kategori di database.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id), // Abaikan kategori saat ini saat cek unik
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id), // Abaikan kategori saat ini saat cek unik
            ],
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->slug),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Menghapus kategori dari database.
     */
    public function destroy(Category $category)
    {
        // Pastikan tidak ada produk yang terhubung sebelum menghapus, atau atur onDelete('cascade') di migrasi
        // Kita sudah pakai onDelete('cascade') di migrasi, jadi ini aman.
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}