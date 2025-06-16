@extends('admin.layouts.app')

@section('title', 'Tambah Produk Baru - Admin MiraTara')

@section('content')
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1">Tambah Produk Baru</h1>
                <p class="text-muted mb-0">
                    Isi detail produk baru untuk ditambahkan ke toko
                </p>
            </div>
            <div>
                <a href="{{ route('admin.products.index_page') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Kembali ke Daftar Produk
                </a>
            </div>
        </div>
    </div>

    <div id="alertContainer" class="mt-3"></div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Detail Produk
                    </h5>
                </div>
                <div class="card-body">
                    <form id="productForm" enctype="multipart/form-data" novalidate>
                        @csrf
                        <input type="hidden" name="_method" value="POST">

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug (URL Friendly) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="slug" name="slug" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Produk</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="category" name="category" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="discount_price" class="form-label">Harga Diskon (Rp)</label>
                                <input type="number" class="form-control" id="discount_price" name="discount_price" min="0" step="0.01">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stock" name="stock" min="0" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="image1" class="form-label">Gambar Utama Produk <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="image1" name="image1" accept="image/*" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="image2" class="form-label">Gambar Sekunder Produk (Opsional)</label>
                            <input type="file" class="form-control" id="image2" name="image2" accept="image/*">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="isActive" name="is_active" checked>
                            <label class="form-check-label" for="isActive">Produk Aktif</label>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <button type="button" class="btn btn-secondary" onclick="resetProductForm()">
                                    <i class="fas fa-undo me-2"></i> Reset Form
                                </button>
                            </div>
                            <div>
                                <a href="{{ route('admin.products.index_page') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitProductBtn">
                                    <span class="btn-text"><i class="fas fa-save me-2"></i> Simpan Produk</span>
                                    <span class="btn-loader d-none"><i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Informasi Tambahan
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled small text-muted">
                                <li>• Field bertanda <span class="text-danger">*</span> wajib diisi</li>
                                <li>• Slug harus unik dan dihasilkan otomatis dari nama produk</li>
                                <li>• Harga diskon harus lebih kecil dari harga normal</li>
                                <li>• Gambar produk akan disimpan di storage</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled small text-muted">
                                <li>• Produk non-aktif tidak akan muncul di website publik</li>
                                <li>• Produk dapat diubah atau dihapus setelah dibuat</li>
                                <li>• Ukuran gambar maksimal 2MB</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endsection

        @push('scripts')
            <script src="{{ asset('js/admin/admin_product_form.js') }}"></script>
        @endpush