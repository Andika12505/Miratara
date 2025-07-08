<?php $__env->startSection('title', 'Keranjang Belanja'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <h2 class="mb-4">Keranjang Belanja Anda</h2>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(Cart::count() > 0): ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th width="120px">Kuantitas</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            
                            <img src="<?php echo e(asset('storage/' . $item->options->image)); ?>" width="60" class="me-3" alt="<?php echo e($item->name); ?>">
                            <span><?php echo e($item->name); ?></span>
                        </div>
                    </td>
                    <td>Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?></td>
                    <td>
                        
                        <form action="<?php echo e(route('cart.update', $item->rowId)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="number" name="quantity" value="<?php echo e($item->qty); ?>" min="1" class="form-control form-control-sm" onchange="this.form.submit()">
                        </form>
                    </td>
                    <td>Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?></td>
                    <td>
                        
                        <form action="<?php echo e(route('cart.remove', $item->rowId)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <div class="row justify-content-end">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Belanja</h5>
                        
                        <h3 class="fw-bold">Rp <?php echo e(Cart::total(0, ',', '.')); ?></h3>
                        <div class="d-grid gap-2 mt-3">
                            <a href="<?php echo e(route('checkout_page')); ?>" class="btn btn-primary">Lanjut ke Checkout</a>
                            <form action="<?php echo e(route('cart.clear')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-outline-danger w-100">Kosongkan Keranjang</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <p class="text-muted">Keranjang belanja Anda masih kosong.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/andika/Documents/Miratara/resources/views/cart/index.blade.php ENDPATH**/ ?>