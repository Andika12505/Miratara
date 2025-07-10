

<?php if($cartItems->isEmpty()): ?>
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
        <h6 class="text-muted">Your cart is empty</h6>
        <p class="text-muted small">Start shopping and add products to your cart</p>
        <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary btn-sm mt-2">
            <i class="fas fa-shopping-bag me-2"></i>Start Shopping
        </a>
    </div>
<?php else: ?>
    
    <div class="cart-items">
        <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="cart-item d-flex align-items-center mb-3 pb-3 border-bottom">
                
                <div class="flex-shrink-0 me-3">
                    <?php
                        $imageUrl = '';
                        if (isset($item->options['image']) && !empty($item->options['image'])) {
                            $imageUrl = asset('images/' . $item->options['image']);
                        }
                    ?>
                    
                    <?php if($imageUrl): ?>
                        <img src="<?php echo e($imageUrl); ?>" 
                             alt="<?php echo e($item->name); ?>" 
                             class="rounded" 
                             style="width: 60px; height: 60px; object-fit: cover;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="bg-light rounded d-none align-items-center justify-content-center" 
                             style="width: 60px; height: 60px;">
                            <i class="fas fa-image text-muted"></i>
                        </div>
                    <?php else: ?>
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px;">
                            <i class="fas fa-image text-muted"></i>
                        </div>
                    <?php endif; ?>
                </div>

                
                <div class="flex-grow-1">
                    <h6 class="mb-1 fw-bold" style="font-size: 0.9rem;"><?php echo e($item->name); ?></h6>
                    
                    
                    <?php if(isset($item->options['has_size']) && $item->options['has_size'] && isset($item->options['size_name'])): ?>
                        <div class="small text-muted mb-1">
                            <span class="badge bg-light text-dark border">
                                Size: <?php echo e($item->options['size_display'] ?? $item->options['size_name']); ?>

                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-primary fw-bold">Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?></span>
                            <div class="small text-muted">Qty: <?php echo e($item->qty); ?></div>
                        </div>
                        <div class="d-flex align-items-center">
                            
                            <div class="btn-group btn-group-sm me-2" role="group">
                                <button type="button" class="btn btn-outline-secondary update-cart-qty" 
                                        data-rowid="<?php echo e($item->rowId); ?>" 
                                        data-action="decrease">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="btn btn-outline-secondary"><?php echo e($item->qty); ?></span>
                                <button type="button" class="btn btn-outline-secondary update-cart-qty" 
                                        data-rowid="<?php echo e($item->rowId); ?>" 
                                        data-action="increase">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            
                            <button type="button" class="btn btn-outline-danger btn-sm remove-cart-item" 
                                    data-rowid="<?php echo e($item->rowId); ?>">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="cart-summary mt-4 pt-3 border-top">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-bold">Subtotal:</span>
            <span class="fw-bold text-primary">
                <?php
                    // Handle the formatted string properly
                    $totalValue = str_replace([',', '.'], ['', ''], $cartTotal);
                    $totalNumeric = intval($totalValue) / 100; // Convert back from cents
                ?>
                Rp <?php echo e(number_format($totalNumeric, 0, ',', '.')); ?>

            </span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <small class="text-muted"><?php echo e($cartCount); ?> item(s)</small>
            <button type="button" class="btn btn-outline-danger btn-sm clear-cart">
                <i class="fas fa-trash me-1"></i>Clear Cart
            </button>
        </div>
    </div>
<?php endif; ?><?php /**PATH /Users/jerenovvidimy/Documents/MiraTaraTest/resources/views/components/cart-offcanvas-content.blade.php ENDPATH**/ ?>