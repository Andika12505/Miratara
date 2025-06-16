

<?php $__env->startSection('title', 'Edit Produk - Admin MiraTara'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1">Edit Produk</h1>
                <p class="text-muted mb-0">
                    Ubah detail produk yang sudah ada
                </p>
            </div>
            <div>
                <a href="<?php echo e(route('admin.products.index_page')); ?>" class="btn btn-outline-secondary">
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
                        <i class="fas fa-edit me-2"></i>
                        Detail Produk
                    </h5>
                </div>
                <div class="card-body">
                    <form id="productForm" enctype="multipart/form-data" novalidate>
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo e(old('name', $product->name)); ?>" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug (URL Friendly) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="slug" name="slug" value="<?php echo e(old('slug', $product->slug)); ?>" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Produk</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo e(old('description', $product->description)); ?></textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="category" name="category" value="<?php echo e(old('category', $product->category)); ?>" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" value="<?php echo e(old('price', $product->price)); ?>" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="discount_price" class="form-label">Harga Diskon (Rp)</label>
                                <input type="number" class="form-control" id="discount_price" name="discount_price" min="0" step="0.01" value="<?php echo e(old('discount_price', $product->discount_price)); ?>">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stock" name="stock" min="0" value="<?php echo e(old('stock', $product->stock)); ?>" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Gambar Produk Saat Ini</label>
                            <?php if($product->image_url_1): ?>
                                <img src="<?php echo e(asset('storage/products/' . basename($product->image_url_1))); ?>" alt="Gambar Utama" style="max-width: 150px; border: 1px solid #ddd; padding: 5px; margin-right: 10px;">
                            <?php endif; ?>
                            <?php if($product->image_url_2): ?>
                                <img src="<?php echo e(asset('storage/products/' . basename($product->image_url_2))); ?>" alt="Gambar Sekunder" style="max-width: 150px; border: 1px solid #ddd; padding: 5px;">
                            <?php endif; ?>
                            <small class="form-text text-muted d-block mt-2">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                        </div>

                        <div class="mb-3">
                            <label for="image1" class="form-label">Ubah Gambar Utama Produk</label>
                            <input type="file" class="form-control" id="image1" name="image1" accept="image/*">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="image2" class="form-label">Ubah Gambar Sekunder Produk (Opsional)</label>
                            <input type="file" class="form-control" id="image2" name="image2" accept="image/*">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="isActive" name="is_active" <?php echo e($product->is_active ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="isActive">Produk Aktif</label>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <button type="button" class="btn btn-secondary" onclick="resetProductForm()">
                                    <i class="fas fa-undo me-2"></i> Reset Form
                                </button>
                            </div>
                            <div>
                                <a href="<?php echo e(route('admin.products.index_page')); ?>" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitProductBtn">
                                    <span class="btn-text"><i class="fas fa-save me-2"></i> Simpan Perubahan</span>
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
        <?php $__env->stopSection(); ?>

        <?php $__env->startPush('scripts'); ?>
            <script src="<?php echo e(asset('js/admin/admin_product_form.js')); ?>"></script>
            <script>
                // Ini untuk mengeset form ke mode edit saat halaman dimuat
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('productForm');
                    if (form) {
                        form.setAttribute('data-product-id', '<?php echo e($product->id); ?>');
                        // Tidak perlu panggil initProductForm('edit') di sini, 
                        // karena admin_product_form.js akan membaca data-product-id secara otomatis
                    }
                });
            </script>
        <?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\RudalJawa\resources\views\admin\products\edit.blade.php ENDPATH**/ ?>