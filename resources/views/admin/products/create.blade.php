{{-- resources/views/admin/products/create.blade.php --}}

@extends('admin.layouts.app') {{-- SESUAIKAN DENGAN LOKASI LAYOUT UTAMA ADMIN KAMU --}}

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tambah Produk Baru</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select class="form-control" id="category_id" name="category_id" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}" required>
                    <small class="form-text text-muted">Akan digunakan di URL, contoh: t-shirt-keren</small>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Gambar Produk</label>
                    <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                    <small class="form-text text-muted">Maksimal 2MB (JPG, PNG, GIF, SVG)</small>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Harga</label>
                    <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" required>
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">Stok</label>
                    <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock') }}" min="0" required>
                </div>
                <div class="mb-3">
                    <label for="metadata" class="form-label">Metadata (JSON)</label>
                    <textarea class="form-control" id="metadata" name="metadata" rows="5" placeholder='{"warna": "merah", "ukuran": ["S", "M"]}'>{{ old('metadata') }}</textarea>
                    <small class="form-text text-muted">Input dalam format JSON, contoh: `{"merek": "ABC", "garansi": "1 tahun"}`</small>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Produk Aktif</label>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Produk</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection