 

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Produk: <?php echo e($product->name); ?></h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('admin.products.update', $product)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?> 

                <div class="mb-3">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select class="form-control" id="category_id" name="category_id" required>
                        <option value="">Pilih Kategori</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $product->category_id) == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo e(old('name', $product->name)); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control" id="slug" name="slug" value="<?php echo e(old('slug', $product->slug)); ?>" required>
                    <small class="form-text text-muted">Akan digunakan di URL, contoh: t-shirt-keren</small>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo e(old('description', $product->description)); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Gambar Produk Saat Ini</label>
                    <?php if($product->image): ?>
                        <div class="mb-2">
                            <img src="<?php echo e(asset($product->image)); ?>" alt="<?php echo e($product->name); ?>" width="150" class="img-thumbnail">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="clear_image" name="clear_image" value="1">
                            <label class="form-check-label" for="clear_image">Hapus Gambar</label>
                        </div>
                    <?php else: ?>
                        <p>Tidak ada gambar saat ini.</p>
                    <?php endif; ?>
                    <input type="file" class="form-control-file mt-2" id="image" name="image" accept="image/*">
                    <small class="form-text text-muted">Pilih gambar baru untuk mengganti yang lama. Maksimal 2MB.</small>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Harga</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?php echo e(old('price', $product->price)); ?>" step="0.01" min="0" required>
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">Stok</label>
                    <input type="number" class="form-control" id="stock" name="stock" value="<?php echo e(old('stock', $product->stock)); ?>" min="0" required>
                </div>
                <div class="mb-3">
                    <label for="metadata" class="form-label">Metadata (JSON)</label>
                    
                    <textarea class="form-control" id="metadata" name="metadata" rows="5" placeholder='{"warna": "merah", "ukuran": ["S", "M"]}'><?php echo e(old('metadata', json_encode($product->metadata, JSON_PRETTY_PRINT))); ?></textarea>
                    <small class="form-text text-muted">Input dalam format JSON, contoh: `{"merek": "ABC", "garansi": "1 tahun"}`</small>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?php echo e(old('is_active', $product->is_active) ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="is_active">Produk Aktif</label>
                </div>
                <button type="submit" class="btn btn-primary">Update Produk</button>
                <a href="<?php echo e(route('admin.products.index_page')); ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/jerenovvidimy/Documents/Shibal/MiraTara/resources/views/admin/products/edit.blade.php ENDPATH**/ ?>