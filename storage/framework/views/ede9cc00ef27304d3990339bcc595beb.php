<div class="row g-4">
    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="product-card">
                <div class="product-image-container position-relative">
                    <?php if($showDiscount && isset($product->metadata['is_discounted']) && $product->metadata['is_discounted']): ?>
                        <div class="discount-badge">
                            <span>DISKON</span>
                        </div>
                    <?php endif; ?>
                    <img src="<?php echo e($product->image ? asset('images/' . $product->image) : asset('images/placeholder.jpg')); ?>"
                         alt="<?php echo e($product->name); ?>"
                         class="product-image"
                         onerror="this.src='https://via.placeholder.com/400x600/f8f9fa/6c757d?text=No+Image'">
                </div>
                <div class="product-info">
                    <h3 class="product-title"><?php echo e($product->name); ?></h3>
                    <div class="product-pricing">
                        <?php if($showDiscount && isset($product->metadata['original_price']) && $product->metadata['original_price']): ?>
                            <span class="current-price">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></span>
                            <span class="original-price">Rp <?php echo e(number_format($product->metadata['original_price'], 0, ',', '.')); ?></span>
                        <?php else: ?>
                            <span class="current-price">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($useFormCart): ?>
                        <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="d-grid add-to-cart-form">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id" value="<?php echo e($product->id); ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="add-to-bag-btn" <?php echo e($product->stock <= 0 ? 'disabled' : ''); ?>>
                                <?php echo e($product->stock <= 0 ? $outOfStockText : $buttonText); ?>

                            </button>
                        </form>
                    <?php else: ?>
                        <button class="add-to-bag-btn"
                                data-product-id="<?php echo e($product->id); ?>"
                                <?php echo e($product->stock <= 0 ? 'disabled' : ''); ?>>
                            <?php echo e($product->stock <= 0 ? $outOfStockText : $buttonText); ?>

                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="text-center py-5">
                <p class="text-muted"><?php echo e($emptyMessage); ?></p>
                <a href="<?php echo e(route('products.index')); ?>" class="<?php echo e($emptyButtonClass); ?> mt-3"><?php echo e($emptyButtonText); ?></a>
            </div>
        </div>
    <?php endif; ?>
</div><?php /**PATH /Users/jerenovvidimy/Documents/MiraTaraTest/resources/views/components/product-grid.blade.php ENDPATH**/ ?>