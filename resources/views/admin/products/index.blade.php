{{-- resources/views/admin/products/index.blade.php --}}

@extends('admin.layouts.app') {{-- SESUAIKAN DENGAN LOKASI LAYOUT UTAMA ADMIN KAMU --}}

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen Produk</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Produk</h6>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">Tambah Produk</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control bg-light border-0 small" placeholder="Cari produk..."
                            aria-label="Search" value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aktif</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ $product->image ? asset('images/' . $product->image) : asset('images/placeholder.jpg') }}" width="80">
                                @else
                                    <i class="fas fa-box-open text-muted" style="font-size: 2em;"></i>
                                @endif
                            </td>
                            <td>{{ $product->name }}<br><small><code>{{ $product->slug }}</code></small></td>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                            <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge badge-success">Ya</span>
                                @else
                                    <span class="badge badge-danger">Tidak</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada produk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $products->links() }} {{-- Tampilkan paginasi --}}
            </div>
        </div>
    </div>
</div>
@endsection