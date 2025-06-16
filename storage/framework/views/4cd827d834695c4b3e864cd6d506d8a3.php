 

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen Produk</h1>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Produk</h6>
            <a href="<?php echo e(route('admin.products.create_page')); ?>" class="btn btn-primary btn-sm">Tambah Produk</a>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.products.index_page')); ?>" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control bg-light border-0 small" placeholder="Cari produk..."
                            aria-label="Search" value="<?php echo e(request('search')); ?>">
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
                        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <?php if($product->image): ?>
                                    <img src="<?php echo e(asset($product->image)); ?>" alt="<?php echo e($product->name); ?>" width="50">
                                <?php else: ?>
                                    <i class="fas fa-box-open text-muted" style="font-size: 2em;"></i>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($product->name); ?><br><small><code><?php echo e($product->slug); ?></code></small></td>
                            <td><?php echo e($product->category->name ?? 'N/A'); ?></td>
                            <td>Rp<?php echo e(number_format($product->price, 0, ',', '.')); ?></td>
                            <td><?php echo e($product->stock); ?></td>
                            <td>
                                <?php if($product->is_active): ?>
                                    <span class="badge badge-success">Ya</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Tidak</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('admin.products.edit_page', $product)); ?>" class="btn btn-warning btn-sm">Edit</a>
                                <form action="<?php echo e(route('admin.products.destroy', $product)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada produk.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php echo e($products->links()); ?> 
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/jerenovvidimy/Documents/Shibal/RudalJawa/resources/views/admin/products/index.blade.php ENDPATH**/ ?>