@extends('admin.layouts.app')

@section('title', 'Kelola Produk - Admin MiraTara')

@section('content')
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1">Kelola Produk</h1>
                <p class="text-muted mb-0">
                    Lihat dan kelola semua produk di toko Anda
                </p>
            </div>
            <div>
                <a href="{{ route('admin.products.create_page') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Produk Baru
                </a>
            </div>
        </div>
    </div>

    <div id="alertContainer" class="mt-3"></div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Cari berdasarkan nama produk atau kategori..." id="productSearchInput">
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-box-open me-2"></i>
                    Daftar Produk
                </h5>
                <span class="badge bg-primary" id="totalProductsBadge">Total: 0 produk</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Gambar</th>
                            <th scope="col">Nama Produk</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Diskon</th>
                            <th scope="col">Stok</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody">
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="loading-state">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-3 text-muted">Memuat data produk...</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted" id="paginationInfo">Menampilkan 0 dari 0 data</small>
                <nav>
                    <ul class="pagination pagination-sm mb-0" id="paginationNav">
                        </ul>
                </nav>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="deleteProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning me-2"></i> Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus produk <strong id="productToDeleteName"></strong>?</p>
                    <div class="alert alert-warning d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div><strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. Produk akan dihapus secara permanen.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i> Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteProduct"><i class="fas fa-trash me-2"></i> Ya, Hapus Produk</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/admin_products.js') }}"></script>
@endpush